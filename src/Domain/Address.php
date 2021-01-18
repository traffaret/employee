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
 * Class Address.
 *
 * @package App\Domain
 */
final class Address
{
    private Entity $entity;

    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
    }

    public function getCountry(): string
    {
        return $this->entity->getAttribute('country');
    }

    public function setCountry(string $country): void
    {
        $country = \trim($country);

        if (empty($country)) {
            throw new ValueError('Country can not be empty');
        }

        $this->entity->update(['country' => $country]);
    }

    public function getCity(): string
    {
        return $this->entity->getAttribute('city');
    }

    public function setCity(string $city): void
    {
        $city = \trim($city);

        if (empty($city)) {
            throw new ValueError('City can not be empty');
        }

        $this->entity->update(['city' => $city]);
    }

    public function getState(): ?string
    {
        return $this->entity->getAttribute('state');
    }

    public function setState(string $state): void
    {
        $this->entity->update(['state' => $state ?: null]);
    }

    public function getStreet(): ?string
    {
        return $this->entity->getAttribute('street');
    }

    public function setStreet(string $street): void
    {
        $this->entity->update(['street' => $street ?: null]);
    }

    public function toArray(): array
    {
        return $this->entity->toArray();
    }
}
