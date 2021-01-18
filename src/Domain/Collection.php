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
 * Interface Collection.
 *
 * @package App\Domain
 */
abstract class Collection extends \ArrayObject
{
    public function filter(callable $callback): self
    {
        $objects = \array_filter($this->getArrayCopy(), $callback);
        $this->exchangeArray($objects);

        return $this;
    }

    public function toArray(): array
    {
        $data = [];

        foreach ($this as $object) {
            if (\method_exists($object, 'toArray')) {
                $data[] = $object->toArray();
            } else {
                $data[] = \get_class($object);
            }
        }

        return $data;
    }
}
