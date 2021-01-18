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

use Psr\Log\LoggerInterface;

/**
 * Class Logger.
 *
 * @package App\Infrastructure
 */
class ConsoleLogger implements LoggerInterface
{
    public const LEVEL_DEBUG = 'debug';

    public const LEVEL_EMERGENCY = 'emergency';

    public const LEVEL_ALERT = 'alert';

    public const LEVEL_CRITICAL = 'critical';

    public const LEVEL_ERROR = 'error';

    public const LEVEL_WARNING = 'warning';

    public const LEVEL_NOTICE = 'notice';

    public const LEVEL_INFO = 'info';

    public function emergency($message, array $context = []): void
    {
        $this->log(self::LEVEL_EMERGENCY, $message, $context);
    }

    public function alert($message, array $context = []): void
    {
        $this->log(self::LEVEL_ALERT, $message, $context);
    }

    public function critical($message, array $context = []): void
    {
        $this->log(self::LEVEL_CRITICAL, $message, $context);
    }

    public function error($message, array $context = []): void
    {
        $this->log(self::LEVEL_ERROR, $message, $context);
    }

    public function warning($message, array $context = []): void
    {
        $this->log(self::LEVEL_WARNING, $message, $context);
    }

    public function notice($message, array $context = []): void
    {
        $this->log(self::LEVEL_NOTICE, $message, $context);
    }

    public function info($message, array $context = []): void
    {
        $this->log(self::LEVEL_INFO, $message, $context);
    }

    public function debug($message, array $context = []): void
    {
        $this->log(self::LEVEL_DEBUG, $message, $context);
    }

    public function log($level, $message, array $context = []): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $console_message = \sprintf(
            "%s [%s] %s\n\n",
            (new \DateTimeImmutable('now'))->format(\DateTimeInterface::ATOM),
            \strtoupper($level),
            \sprintf('%s %s', $message, \json_encode($context, JSON_THROW_ON_ERROR))
        );

        \fwrite(\STDOUT, $console_message);
    }
}
