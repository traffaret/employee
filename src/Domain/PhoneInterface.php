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
 * Class PhoneInterface.
 *
 * @package App\Domain
 */
interface PhoneInterface
{
    public function getCountryCode(): string;

    public function getCityCode(): string;

    public function getNumber(): string;

    public function toArray();
}
