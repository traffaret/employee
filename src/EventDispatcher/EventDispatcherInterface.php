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

use App\EventDispatcher\Events\Event;
use App\EventDispatcher\Observers\Observer;

/**
 * Interface EventDispatcherInterface.
 *
 * @package App\EventDispatcher
 */
interface EventDispatcherInterface
{
    public function attach(Observer $observer, string $event): void;

    public function detach(Observer $observer, string $event): void;

    public function dispatch(Event $event): void;
}
