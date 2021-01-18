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
 * Class ServiceData.
 *
 * @package App\DataTransferObject
 */
final class ObjectData implements DataTransferObject
{
    private object $object;

    public function __construct(object $object)
    {
        $this->object = $object;
    }

    public function __get($name)
    {
        // TODO:
    }

    public function __set($name, $value)
    {
        // TODO:
    }

    public function __isset($name)
    {
        // TODO:
    }

    public function toArray(): array
    {
        if (! \method_exists($this->object, 'toArray')) {
            throw new \InvalidArgumentException(
                \sprintf('Object "%s" must have "toArray" method', \get_class($this->object))
            );
        }

        return $this->object->toArray();
    }
}
