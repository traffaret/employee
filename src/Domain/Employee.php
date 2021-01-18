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

namespace App\Domain;

use App\Exceptions\ValueError;

/**
 * Class Employee.
 *
 * @package App\Domain
 */
final class Employee
{
    public const STATUS_ARCHIVED = 'archived';

    public const STATUS_ACTIVE = 'active';

    private Entity $entity;

    public function __construct(Entity $entity) {
        $this->entity = $entity;
    }

    public function getId(): ?int
    {
        return $this->entity->getAttribute('id');
    }

    public function setId(int $id): void
    {
        $this->entity->update(['id' => $id]);
    }

    public function getName(): string
    {
        return $this->entity->getAttribute('name');
    }

    public function getMiddleName(): ?string
    {
        return $this->entity->getAttribute('middle_name');
    }

    public function getLastName(): string
    {
        return $this->entity->getAttribute('last_name');
    }

    public function setName(string $name): void
    {
        $name = \trim($name);

        if (empty($name)) {
            throw new ValueError('Name can not be empty');
        }

        $this->entity->update(['name' => $name]);
    }

    public function setMiddleName(string $middle_name): void
    {
        $this->entity->update(['middle_name' => $middle_name ?: null]);
    }

    public function setLastName(string $last_name): void
    {
        $last_name = \trim($last_name);

        if (empty($last_name)) {
            throw new ValueError('Last name can not be empty');
        }

        $this->entity->update(['last_name' => $last_name]);
    }

    public function getPhones(): Collection
    {
        $phones = $this->entity->getAttribute('phones');
        $collection = new PhoneCollection([]);

        foreach ($phones as $phone) {
            $collection[] = new Phone(new PhoneEntity($phone));
        }

        return $collection;
    }

    public function addPhone(PhoneInterface $phone): void
    {
        $phones = $this->entity->getAttribute('phones') ?? [];

        $this->entity->update(['phones' => \array_merge($phones, [$phone->toArray()])]);
    }

    public function deletePhone(PhoneInterface $phone): void
    {
        $phones = [];

        /** @var Phone $p */
        foreach ($this->getPhones() as $p) {
            if (
                $p->getCountryCode() === $phone->getCountryCode()
                && $p->getCityCode() === $phone->getCityCode()
                && $p->getNumber() === $phone->getNumber()
            ) {
                continue;
            }

            $phones[] = $p->toArray();
        }

        $this->entity->update(['phones' => $phones]);
    }

    public function getAddress(): Address
    {
        return new Address(new AddressEntity($this->entity->getAttribute('address')));
    }

    public function setAddress(Address $address): void
    {
        $this->entity->update(['address' => $address->toArray()]);
    }

    public function getStatus(): StatusInterface
    {
        return new Status(new StatusEntity($this->entity->getAttribute('status')));
    }

    public function setStatus(StatusInterface $status): void
    {
        $this->entity->update(['status' => $status->toArray()]);
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return new \DateTimeImmutable($this->entity->getAttribute('created_at'));
    }

    public function setCreatedAt(\DateTimeInterface $date_time): void
    {
        $this->entity->update(['created_at' => $date_time->format(\DateTimeInterface::ATOM)]);
    }

    public function getUpdatedAt(): \DateTimeInterface
    {
        return new \DateTimeImmutable($this->entity->getAttribute('updated_at'));
    }

    public function setUpdatedAt(\DateTimeInterface $date_time): void
    {
        $this->entity->update(['updated_at' => $date_time->format(\DateTimeInterface::ATOM)]);
    }

    public function toArray(): array
    {
        return \array_merge(
            $this->entity->toArray(),
            ['id' => $this->getId()]
        );
    }
}

