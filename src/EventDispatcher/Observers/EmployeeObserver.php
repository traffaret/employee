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

namespace App\EventDispatcher\Observers;

use App\EventDispatcher\Events\EmployeeEvent;
use App\EventDispatcher\Events\Event;
use Psr\Log\LoggerInterface;

/**
 * Interface EmployeeObserver.
 *
 * @package App\EventDispatcher\Observers
 */
final class EmployeeObserver implements Observer
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function notify(Event $event): void
    {
        $context = [];

        if ($event instanceof EmployeeEvent) {
            $context = $event->getEmployee()->toArray();
        }

        $this->logger->info($event->getName(), $context);
    }
}
