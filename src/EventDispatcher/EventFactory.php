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

/**
 * Interface EventFactory.
 *
 * @package App\EventDispatcher
 */
interface EventFactory
{
    public function createEvent(string $event, ...$data): Event;
}
