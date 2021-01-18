# Employee

```php
use App\ApplicationService;
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

$event_dispatcher = new EventDispatcher();
$employee_observer = new EmployeeObserver(new ConsoleLogger());

$event_dispatcher->attach($employee_observer, EmployeeAdded::NAME);
$event_dispatcher->attach($employee_observer, EmployeeUpdated::NAME);
$event_dispatcher->attach($employee_observer, EmployeeDeleted::NAME);

$repository = new EmployeeInMemoryFromFileRepository('employee.json', $event_dispatcher, $event_dispatcher);
$status_repository = new StatusInMemoryFromFileRepository('status.json');

$service = new EmployeeService($repository, $status_repository);

$app_service = new ApplicationService($service);

$app_service->getEmployeeList();
```

# Test

```bash
composer test
```
