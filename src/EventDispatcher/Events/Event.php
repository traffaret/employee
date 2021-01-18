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

/**
 * Interface Event.
 *
 * @package App\EventDispatcher
 */
interface Event
{
    public function getName(): string;
}
