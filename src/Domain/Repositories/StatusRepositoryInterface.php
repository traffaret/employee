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

namespace App\Domain\Repositories;

use App\Domain\Collection;
use App\Domain\StatusInterface;

/**
 * Interface StatusRepositoryInterface.
 *
 * @package App\Domain\Repositories
 */
interface StatusRepositoryInterface extends Repository
{
    public function getByName(string $name): StatusInterface;

    public function save(StatusInterface $status): void;

    public function delete(StatusInterface $status): void;
}
