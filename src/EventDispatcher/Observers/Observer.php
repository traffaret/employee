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

use App\EventDispatcher\Events\Event;

/**
 * Class Observer.
 *
 * @package App\EventDispatcher
 */
interface Observer
{
    public function notify(Event $event): void;
}
