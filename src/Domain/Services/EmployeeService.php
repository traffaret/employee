<?php

/**
 * Created by IntelliJ IDEA.
 *
 * PHP version 7.3
 *
 * @category employee
 * @author   Oleg Tikhonov <to@toro.one>
 */

declare(strict_types=1);

namespace App\Domain\Services;

use App\DataTransferObject\DataTransferObject;
use App\DataTransferObject\ObjectData;
use App\Domain\Address;
use App\Domain\AddressEntity;
use App\Domain\Employee;
use App\Domain\EmployeeEntity;
use App\Domain\Phone;
use App\Domain\PhoneEntity;
use App\Domain\Repositories\EmployeeRepositoryInterface;
use App\Domain\Repositories\StatusRepositoryInterface;
use App\Exceptions\ObjectNotFound;
use App\Exceptions\ValueError;

/**
 * Class EmployeeService.
 *
 * @package App\Domain\Services
 */
final class EmployeeService
{
    private EmployeeRepositoryInterface $repository;

    private StatusRepositoryInterface $status_repository;

    public function __construct(
        EmployeeRepositoryInterface $repository,
        StatusRepositoryInterface $status_repository
    ) {
        $this->repository = $repository;
        $this->status_repository = $status_repository;
    }

    public function getById(int $employee_id): DataTransferObject
    {
        return new ObjectData($this->repository->findById($employee_id));
    }

    public function deleteById(int $employee_id): void
    {
        $employee = $this->repository->findById($employee_id);
        $this->repository->delete($employee);
    }

    public function getList(): DataTransferObject
    {
        return new ObjectData($this->repository->getAll());
    }

    public function create(array $employee_data, array $address_data, array $phones): void
    {
        if (empty($phones)) {
            throw new ValueError('Employee must have at least one phone number');
        }

        $employee = new Employee(new EmployeeEntity($employee_data));

        $employee->setName($employee_data['name'] ?? '');
        $employee->setLastName($employee_data['last_name'] ?? '');
        $employee->setMiddleName($employee_data['middle_name'] ?? '');

        $address = new Address(new AddressEntity($address_data));

        $address->setCountry($address_data['country'] ?? '');
        $address->setCity($address_data['city'] ?? '');

        $employee->setAddress($address);

        foreach ($phones as $phone_data) {
            $phone = new Phone(new PhoneEntity([]));
            $phone->setCityCode($phone_data['city_code'] ?? '');
            $phone->setCountryCode($phone_data['country_code'] ?? '');
            $phone->setNumber($phone_data['number'] ?? '');

            $employee->addPhone($phone);
        }

        $this->repository->save($employee);
    }

    public function setName(
        int $employee_id,
        ?string $name = null,
        ?string $last_name = null,
        ?string $middle_name = null
    ): void {
        $employee = $this->repository->findById($employee_id);

        if (empty($name) && empty($last_name) && empty($middle_name)) {
            throw new \Error('Nothing to update');
        }

        if (null !== $name) {
            $employee->setName($name);
        }

        if (null !== $last_name) {
            $employee->setLastName($last_name);
        }

        if (null !== $middle_name) {
            $employee->setMiddleName($middle_name);
        }

        $this->repository->save($employee);
    }

    public function changeAddress(int $employee_id, array $address_data): void
    {
        $employee = $this->repository->findById($employee_id);

        $address = new Address(new AddressEntity($address_data));

        $address->setCountry($address_data['country'] ?? '');
        $address->setCity($address_data['city'] ?? '');

        $employee->setAddress($address);

        $this->repository->save($employee);
    }

    public function addPhone(int $employee_id, string $country_code, string $city_code, string $number): void
    {
        $employee = $this->repository->findById($employee_id);

        $phone = new Phone(new PhoneEntity([]));

        $phone->setCountryCode($country_code);
        $phone->setCityCode($city_code);
        $phone->setNumber($number);

        $employee->addPhone($phone);

        $this->repository->save($employee);
    }

    public function deletePhone(int $employee_id, string $country_code, string $city_code, string $number): void
    {
        $employee = $this->repository->findById($employee_id);

        $phones = $employee->getPhones();

        if (1 === \count($phones)) {
            throw new \LogicException('Can not remove phone number');
        }

        $is_deleted = false;

        /** @var Phone $phone */
        foreach ($phones as $phone) {
            if (
                $phone->getCountryCode() === $country_code
                && $phone->getCityCode() === $city_code
                && $phone->getNumber() === $number
            ) {
                $employee->deletePhone($phone);
                $this->repository->save($employee);
                $is_deleted = true;
                break;
            }
        }

        if (! $is_deleted) {
            throw new ObjectNotFound(
                \sprintf('Phone not found "%s"', \implode(' ', [$country_code, $city_code, $number]))
            );
        }
    }

    public function toArchive(int $employee_id): void
    {
        $employee = $this->repository->findById($employee_id);
        $status = $this->status_repository->getByName(Employee::STATUS_ARCHIVED);

        $employee->setStatus($status);

        $this->repository->save($employee);
    }

    public function fromArchive(int $employee_id): void
    {
        $employee = $this->repository->findById($employee_id);
        $status = $this->status_repository->getByName(Employee::STATUS_ACTIVE);

        $employee->setStatus($status);

        $this->repository->save($employee);
    }
}
