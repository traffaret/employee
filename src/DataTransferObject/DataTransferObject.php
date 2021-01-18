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

namespace App\DataTransferObject;

/**
 * Interface DataTransferObject.
 *
 * @package App\DataTransferObject
 */
interface DataTransferObject
{
    public function toArray(): array;
}
