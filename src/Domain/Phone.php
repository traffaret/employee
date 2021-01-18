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

use App\Exceptions\ValueError;

/**
 * Class EmployeePhone.
 *
 * @package App\Domain
 */
final class Phone implements PhoneInterface
{
    private Entity $entity;

    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
    }

    public function getCountryCode(): string
    {
        return $this->entity->getAttribute('country_code');
    }

    public function setCountryCode(string $country_code): void
    {
        $country_code = \trim($country_code);

        if (empty($country_code)) {
            throw new ValueError('Country code can not be empty');
        }

        $this->entity->update(['country_code' => $country_code]);
    }

    public function getCityCode(): string
    {
        return $this->entity->getAttribute('city_code');
    }

    public function setCityCode(string $code): void
    {
        $code = \trim($code);

        if (empty($code)) {
            throw new ValueError('City code can not be empty');
        }

        $this->entity->update(['city_code' => $code]);
    }

    public function getNumber(): string
    {
        return $this->entity->getAttribute('number');
    }

    public function setNumber(string $number): void
    {
        $number = \trim($number);

        if (empty($number)) {
            throw new ValueError('Number can not be empty');
        }

        $this->entity->update(['number' => $number]);
    }

    public function toArray(): array
    {
        return $this->entity->toArray();
    }
}
