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

namespace App\Domain;

/**
 * Interface StatusInterface.
 *
 * @package App\Domain
 */
interface StatusInterface
{
    public function getName(): string;

    public function toArray(): array;
}
