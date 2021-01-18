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

namespace App\EventDispatcher\Events;

use App\Domain\Employee;

/**
 * Class EmployeeDeleted.
 *
 * @package App\EventDispatcher\Events
 */
final class EmployeeDeleted implements EmployeeEvent
{
    public const NAME = 'employee:deleted';

    private Employee $employee;

    public function __construct(Employee $employee)
    {
        $this->employee = $employee;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getEmployee(): Employee
    {
        return $this->employee;
    }
}
