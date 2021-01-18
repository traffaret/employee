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
 * Class Status.
 *
 * @package App\Domain
 */
final class Status implements StatusInterface
{
    private Entity $entity;

    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
    }

    public function getName(): string
    {
        return $this->entity->getAttribute('name');
    }

    public function setName(string $name): void
    {
        $name = \trim($name);

        if (empty($name)) {
            throw new ValueError('Status name can not be empty');
        }

        $this->entity->update(['name' => $name]);
    }

    public function toArray(): array
    {
        return $this->entity->toArray();
    }
}
