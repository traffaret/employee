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

namespace App\Domain\Repositories;

use App\Domain\Collection;
use App\Domain\Employee;
use App\Domain\PhoneInterface;
use App\Domain\StatusInterface;

/**
 * Interface EmployeeRepository.
 *
 * @package App\Domain
 */
interface EmployeeRepositoryInterface extends Repository
{
    public function findById(int $id): Employee;

    public function getActive(): Collection;

    public function getArchived(): Collection;

    public function delete(Employee $employee): void;

    public function save(Employee $employee): void;
}
