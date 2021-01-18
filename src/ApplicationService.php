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

namespace App;

use App\DataTransferObject\DataTransferObject;
use App\Domain\Services\EmployeeService;

/**
 * Class EmployeeService.
 *
 * @package App\Services
 */
final class ApplicationService
{
    private EmployeeService $employee_service;

    public function __construct(
        EmployeeService $employee_service
    ) {
        $this->employee_service = $employee_service;
    }

    public function getEmployeeList(): DataTransferObject
    {
        return $this->employee_service->getList();
    }

    public function getEmployeeById(int $employee_id): DataTransferObject
    {
        return $this->employee_service->getById($employee_id);
    }
}
