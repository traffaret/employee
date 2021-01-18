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

namespace App\Tests\Integration;

use App\Domain\Employee;
use App\Domain\Services\EmployeeService;
use App\EventDispatcher\EventDispatcher;
use App\EventDispatcher\Events\EmployeeAdded;
use App\EventDispatcher\Events\EmployeeDeleted;
use App\EventDispatcher\Events\EmployeeUpdated;
use App\EventDispatcher\Observers\EmployeeObserver;
use App\Exceptions\ObjectNotFound;
use App\Exceptions\ValueError;
use App\Infrastructure\ConsoleLogger;
use App\Infrastructure\EmployeeInMemoryFromFileRepository;
use App\Infrastructure\StatusInMemoryFromFileRepository;
use PHPUnit\Framework\TestCase;

/**
 * Class EmployeeTest.
 *
 * @package App\Tests\Integration
 */
class EmployeeTest extends TestCase
{
    public const FIXTURES = __DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR;

    private static EmployeeService $service;

    public static function setUpBeforeClass(): void
    {
        $event_dispatcher = new EventDispatcher();
        $employee_observer = new EmployeeObserver(new ConsoleLogger());

        $event_dispatcher->attach($employee_observer, EmployeeAdded::NAME);
        $event_dispatcher->attach($employee_observer, EmployeeUpdated::NAME);
        $event_dispatcher->attach($employee_observer, EmployeeDeleted::NAME);

        $repository = new EmployeeInMemoryFromFileRepository(self::FIXTURES . 'employee.json', $event_dispatcher, $event_dispatcher);
        $status_repository = new StatusInMemoryFromFileRepository(self::FIXTURES . 'status.json');

        self::$service = new EmployeeService($repository, $status_repository);
    }

    /**
     * testGetList.
     *
     * @covers \App\Domain\Services\EmployeeService::getList
     *
     * @return void
     */
    public function testGetList(): void
    {
        $list = self::$service->getList()->toArray();

        self::assertCount(5, $list);
    }

    /**
     * testGetById.
     *
     * @param int $id
     *
     * @covers \App\Domain\Services\EmployeeService::getById
     * @testWith    [0]
     *              [4]
     *
     * @return void
     */
    public function testGetById(int $id): void
    {
        $employee = self::$service->getById($id);

        self::assertSame($id, $employee->toArray()['id']);
    }

    /**
     * testDeleteById.
     *
     * @covers \App\Domain\Services\EmployeeService::deleteById
     *
     * @return void
     */
    public function testDeleteById(): void
    {
        self::$service->deleteById(2);

        self::assertCount(4, self::$service->getList()->toArray());
    }

    /**
     * testCreate.
     *
     * @covers \App\Domain\Services\EmployeeService::create
     *
     * @return void
     */
    public function testCreate(): void
    {
        $employee_data = [
            'name' => 'Integration',
            'last_name' => 'Test',
            'status' => ['name' => Employee::STATUS_ACTIVE],
        ];

        $address_data = [
            'country' => 'Russia',
            'city' => 'St. Petersburg',
            'state' => 'St. Petersburg',
            'street' => 'st. Lenina',
        ];

        $phones = [
            [
                'country_code' => '8',
                'city_code' => '812',
                'number' => '1234567',
            ]
        ];

        self::$service->create($employee_data, $address_data, $phones);

        $employee = self::$service->getById(5)->toArray();

        foreach ($employee_data as $k => $v) {
            self::assertContains($v, $employee);
        }

        foreach ($address_data as $k => $v) {
            self::assertContains($v, $employee['address']);
        }

        self::assertNotEmpty($employee['phones']);
    }

    /**
     * testSetName.
     *
     * @covers \App\Domain\Services\EmployeeService::setName
     *
     * @return void
     */
    public function testSetName(): void
    {
        $employee = self::$service->getById(5);

        self::$service->setName(5, 'ChangedName');

        self::assertNotSame('ChangedName', $employee->toArray()['name']);
        self::assertSame('ChangedName', self::$service->getById(5)->toArray()['name']);
    }

    /**
     * testChangeAddress.
     *
     * @covers \App\Domain\Services\EmployeeService::changeAddress
     *
     * @return void
     */
    public function testChangeAddress(): void
    {
        $employee = self::$service->getById(5);

        self::$service->changeAddress(5, ['country' => 'Russia', 'city' => 'St.Petersburg', 'street' => 'ulitsa Mira']);

        self::assertNotSame('ulitsa Mira', $employee->toArray()['address']['street'] ?? null);
        self::assertSame('ulitsa Mira', self::$service->getById(5)->toArray()['address']['street']);
    }

    /**
     * testAddPhone.
     *
     * @covers \App\Domain\Services\EmployeeService::addPhone
     *
     * @return void
     */
    public function testAddPhone(): void
    {
        $employee = self::$service->getById(1)->toArray();

        self::$service->addPhone(1, '8', '812', '7654321');

        $new_employee = self::$service->getById(1)->toArray();

        self::assertCount(1, $employee['phones']);
        self::assertCount(2, $new_employee['phones']);
    }

    /**
     * testDeletePhone.
     *
     * @covers \App\Domain\Services\EmployeeService::deletePhone
     *
     * @throws \App\Exceptions\ObjectNotFound
     * @return void
     */
    public function testDeletePhone(): void
    {
        $employee = self::$service->getById(1)->toArray();

        self::$service->deletePhone(1, '8', '800', '5553535');

        self::assertCount(2, $employee['phones']);
        self::assertCount(1, self::$service->getById(1)->toArray()['phones']);
    }

    /**
     * testToArchive.
     *
     * @covers \App\Domain\Services\EmployeeService::toArchive
     *
     * @return void
     */
    public function testToArchive(): void
    {
        $employee = self::$service->getById(5)->toArray();

        self::$service->toArchive(5);

        $new_employee = self::$service->getById(5)->toArray();

        self::assertNotSame($employee['status']['name'], $new_employee['status']['name']);
        self::assertSame(Employee::STATUS_ARCHIVED, $new_employee['status']['name']);
    }

    /**
     * testFromArchive.
     *
     * @covers \App\Domain\Services\EmployeeService::fromArchive
     *
     * @return void
     */
    public function testFromArchive(): void
    {
        $employee = self::$service->getById(5)->toArray();

        self::$service->fromArchive(5);

        $new_employee = self::$service->getById(5)->toArray();

        self::assertNotSame($employee['status']['name'], $new_employee['status']['name']);
        self::assertSame(Employee::STATUS_ACTIVE, $new_employee['status']['name']);
    }

    /**
     * testItCanNoFindEmployee.
     *
     * @covers \App\Domain\Services\EmployeeService::getById
     *
     * @return void
     */
    public function testItCanNoFindEmployee(): void
    {
        $this->expectException(ObjectNotFound::class);
        self::$service->getById(10000);
    }

    /**
     * testCanNotDeleteLastPhone.
     *
     * @covers \App\Domain\Services\EmployeeService::deletePhone
     *
     * @throws \App\Exceptions\ObjectNotFound
     * @return void
     */
    public function testCanNotDeleteLastPhone(): void
    {
        $this->expectException(\LogicException::class);
        self::$service->deletePhone(1, '8', '800', '5553535');
    }

    /**
     * testCanNotSaveEmptyAddress.
     *
     * @covers \App\Domain\Services\EmployeeService::changeAddress
     *
     * @return void
     */
    public function testCanNotSaveEmptyAddress(): void
    {
        $this->expectException(ValueError::class);
        self::$service->changeAddress(1, ['city' => '']);
    }
}
