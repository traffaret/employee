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

namespace App\Infrastructure;

use App\Domain\Collection;
use App\Domain\Employee;
use App\Domain\EmployeeCollection;
use App\Domain\EmployeeEntity;
use App\Domain\EmployeeHistory;
use App\Domain\Repositories\EmployeeRepositoryInterface;
use App\EventDispatcher\EventFactory;
use App\EventDispatcher\Events\EmployeeAdded;
use App\EventDispatcher\EventDispatcher;
use App\EventDispatcher\Events\EmployeeDeleted;
use App\EventDispatcher\Events\EmployeeUpdated;
use App\Exceptions\ObjectNotFound;

/**
 * Class FilenameEmployeeRepository.
 *
 * @package App\Infrastructure
 */
final class EmployeeInMemoryFromFileRepository extends InMemoryFromFileRepository implements EmployeeRepositoryInterface
{
    protected EventDispatcher $dispatcher;

    protected EventFactory $event_factory;

    public function __construct(
        string $filename,
        ?EventDispatcher $dispatcher = null,
        ?EventFactory $event_factory = null
    ) {
        parent::__construct($filename);

        $this->dispatcher = $dispatcher ?? new EventDispatcher();
        $this->event_factory = $event_factory ?? new EventDispatcher();
    }

    public function findById(int $id): Employee
    {
        if (! isset($this->data[$id])) {
            throw new ObjectNotFound(\sprintf('Employee with id "%s" does not exist', $id));
        }

        return new Employee(new EmployeeEntity($this->data[$id]));
    }

    public function getActive(): Collection
    {
        return $this->getAll()->filter(
            static function (Employee $employee): bool {
                return Employee::STATUS_ACTIVE === $employee->getStatus()->getName();
            }
        );
    }

    public function getArchived(): Collection
    {
        return $this->getAll()->filter(
            static function (Employee $employee): bool {
                return Employee::STATUS_ARCHIVED === $employee->getStatus()->getName();
            }
        );
    }

    public function delete(Employee $employee): void
    {
        unset($this->data[$employee->getId()]);

        $this->dispatcher->dispatch($this->event_factory->createEvent(EmployeeDeleted::NAME, $employee));
    }

    public function save(Employee $employee): void
    {
        if (null === $employee->getId()) {
            $id = (int) \array_key_last($this->data) + 1;

            $employee->setId($id);
            $employee->setCreatedAt(new \DateTimeImmutable('now'));
            $this->data[$employee->getId()] = $employee->toArray();

            $this->dispatcher->dispatch($this->event_factory->createEvent(EmployeeAdded::NAME, $employee));
        } else {
            $employee->setUpdatedAt(new \DateTimeImmutable('now'));
            $this->data[$employee->getId()] = $employee->toArray();

            $this->dispatcher->dispatch($this->event_factory->createEvent(EmployeeUpdated::NAME, $employee));
        }

        $this->saveStatusHistory($employee);
    }

    public function saveStatusHistory(Employee $employee): void
    {
        if (! isset($this->data[$employee->getId()]['history']['status'])) {
            $this->data[$employee->getId()]['history']['status'] = [];
        }

        $this->data[$employee->getId()]['history']['status'][] = [
            'name' => $employee->getStatus()->getName(),
            'created_at' => (new \DateTimeImmutable('now'))->format(\DateTimeInterface::ATOM),
        ];
    }

    public function getHistory(Employee $employee): Collection
    {
        $history = $this->data[$employee->getId()]['history'] ?? [];
        $collection = new EmployeeHistory([]);

        foreach ($history as $h) {
            $collection[] = (object) $h;
        }

        return $collection;
    }

    public function getAll(): Collection
    {
        $collection = new EmployeeCollection([]);

        foreach ($this->data as $data) {
            $collection[] = new Employee((new EmployeeEntity($data)));
        }

        return $collection;
    }
}
