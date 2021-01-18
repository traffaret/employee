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
 * Interface EmployeeEvent.
 *
 * @package App\EventDispatcher\Events
 */
interface EmployeeEvent extends Event
{
    public function getEmployee(): Employee;
}
