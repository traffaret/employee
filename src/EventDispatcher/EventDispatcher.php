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

namespace App\EventDispatcher;

use App\EventDispatcher\Events\EmployeeAdded;
use App\EventDispatcher\Events\EmployeeDeleted;
use App\EventDispatcher\Events\EmployeeUpdated;
use App\EventDispatcher\Events\Event;
use App\EventDispatcher\Observers\Observer;

/**
 * Interface EventDispatcher.
 *
 * @package App\EventDispatcher
 */
final class EventDispatcher implements EventDispatcherInterface, EventFactory
{
    private array $observers = [];

    public function attach(Observer $observer, string $event): void
    {
        if (! isset($this->observers[$event])) {
            $this->observers[$event] = [];
        }

        $this->observers[$event][] = $observer;
    }

    public function detach(Observer $observer, string $event): void
    {
        foreach ($this->getObservers($event) as $k => $o) {
            if ($observer === $o) {
                unset($this->observers[$event][$k]);
                break;
            }
        }
    }

    public function dispatch(Event $event): void
    {
        /** @var Observer $observer */
        foreach ($this->getObservers($event->getName()) as $observer) {
            $observer->notify($event);
        }
    }

    public function getObservers(string $event = null): array
    {
        if (null === $event) {
            return $this->observers;
        }

        return $this->observers[$event] ?? [];
    }

    public function createEvent(string $event, ...$data): Event
    {
        if (EmployeeAdded::NAME === $event) {
            return new EmployeeUpdated(...$data);
        }

        if (EmployeeUpdated::NAME === $event) {
            return new EmployeeUpdated(...$data);
        }

        if (EmployeeDeleted::NAME === $event) {
            return new EmployeeDeleted(...$data);
        }

        throw new \RuntimeException(\sprintf('Factory for the event "%s" not implemented', $event));
    }
}
