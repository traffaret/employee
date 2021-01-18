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

use App\Domain\Repositories\Repository;

/**
 * Class FileRepository.
 *
 * @package App\Infrastructure
 */
abstract class InMemoryFromFileRepository implements Repository
{
    public const FILE_JSON = 'json';

    public const ALLOWED_FILE_TYPES = [
        self::FILE_JSON,
    ];

    private string $filename;

    protected array $data = [];

    public function __construct(
        string $filename
    ) {
        $this->filename = $filename;

        $this->load();
    }

    private function load(): void
    {
        if (! \is_readable($this->filename)) {
            throw new \RuntimeException(\sprintf('Can not read file "%s"', $this->filename));
        }

        $ext_pos = \strpos($this->filename, '.');

        if (false === $ext_pos) {
            throw new \InvalidArgumentException(\sprintf('Unrecognized extension for the file "%s"', $this->filename));
        }

        $data_type = \trim(\substr($this->filename, $ext_pos + 1));

        if (! \in_array($data_type, self::ALLOWED_FILE_TYPES)) {
            throw new \InvalidArgumentException(\sprintf('Invalid data type "%s"', $data_type));
        }

        if (self::FILE_JSON === $data_type) {
            try {
                $this->data = \json_decode(\file_get_contents($this->filename), true, 512, \JSON_THROW_ON_ERROR);
            } catch (\JsonException $e) {
                throw new \InvalidArgumentException(\sprintf('Can not parse data from the file "%s": %s', $this->filename, $e->getMessage()));
            }
        }
    }
}
