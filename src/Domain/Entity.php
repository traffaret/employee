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
 * Interface Entity.
 *
 * @package App\Domain
 */
abstract class Entity
{
    protected array $attributes;

    public function __construct(array $data = [])
    {
        $this->attributes = $data;
    }

    public function getAttribute(string $name)
    {
        return $this->attributes[$name] ?? null;
    }

    public function update(array $data): void
    {
        $this->attributes = \array_merge($this->attributes, $data);
    }

    public function toArray(): array
    {
        $attributes = $this->attributes;

        foreach ($attributes as $k => $v) {
            if ($v instanceof self) {
                $attributes[$k] = $v->toArray();
            } elseif ($v instanceof \DateTimeInterface) {
                $attributes[$k] = $v->format(\DateTimeInterface::ATOM);
            }
        }

        return $attributes;
    }
}
