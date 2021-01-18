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

namespace App\Infrastructure;

use App\Domain\Collection;
use App\Domain\Repositories\StatusRepositoryInterface;
use App\Domain\Status;
use App\Domain\StatusCollection;
use App\Domain\StatusEntity;
use App\Domain\StatusInterface;
use App\Exceptions\ObjectNotFound;

/**
 * Class StatusFileRepository.
 *
 * @package App\Infrastructure
 */
final class StatusInMemoryFromFileRepository extends InMemoryFromFileRepository implements StatusRepositoryInterface
{
    public function getAll(): Collection
    {
        $collection = new StatusCollection([]);

        foreach ($this->data as $data) {
            $collection[] = new Status(new StatusEntity($data));
        }

        return $collection;
    }

    public function getByName(string $name): StatusInterface
    {
        /** @var StatusInterface $status */
        foreach ($this->getAll() as $status) {
            if ($name === $status->getName()) {
                return $status;
            }
        }

        throw new ObjectNotFound(\sprintf('Status with name "%s" not found', $name));
    }

    public function save(StatusInterface $status): void
    {
        $this->data[] = $status->toArray();
    }

    public function delete(StatusInterface $status): void
    {
        foreach ($this->data as $k => $v) {
            if ($v['name'] === $status->getName()) {
                unset($this->data[$k]);
                break;
            }
        }
    }
}
