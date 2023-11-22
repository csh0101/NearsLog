<?php

namespace NearsLog;

use Monolog\Formatter\JsonFormatter;
use Monolog\Logger as MonoLogger;
use Monolog\Handler\StreamHandler;

use Monolog\ErrorHandler;

class Logger
{

    public const DEBUG = 100;

    /**
     * Interesting events
     *
     * Examples: User logs in, SQL logs.
     */
    public const INFO = 200;

    /**
     * Uncommon events
     */
    public const NOTICE = 250;

    /**
     * Exceptional occurrences that are not errors
     *
     * Examples: Use of deprecated APIs, poor use of an API,
     * undesirable things that are not necessarily wrong.
     */
    public const WARNING = 300;

    /**
     * Runtime errors
     */
    public const ERROR = 400;
    private static $loggerInstance;
    private static $bizName;
    private static $loggerName;
    private static $logFilePath;
    private static $logLevel;


    private function __construct()
    {
    }

    public static function setConfig($bizName, $loggerName, $logFilePath, $logLevel)
    {
        self::$bizName = $bizName;
        self::$loggerName = $loggerName;
        self::$logFilePath = $logFilePath;
        self::$logLevel = $logLevel;
    }

    public static function getLogFilePath()
    {
        return self::$logFilePath;
    }

    public static function getBizName()
    {
        return self::$bizName;
    }

    public static function getLoggerName()
    {
        return self::$loggerName;
    }


    /**
        @reurn MonoLogger
     **/
    public static function getLogger()
    {
        if (self::$loggerInstance == null) {
            self::$loggerInstance = self::createLoggerInstance();
        }

        return self::$loggerInstance;
    }

    private static function createLoggerInstance()
    {
        $logLevel = self::$logLevel ?: MonoLogger::DEBUG;
        $logger = new MonoLogger(self::$loggerName ?: 'default');
        $formatter = new JsonFormatter();
        $file_handler = new StreamHandler(self::$logFilePath ?: '/tmp/biz.log', $logLevel);
        $file_handler->setFormatter($formatter);
        $logger->pushHandler($file_handler);
        $logger->pushProcessor(function ($record) {
            $record['extra']['biz_name'] = self::$bizName ?: 'default';
            return $record;
        });
        return $logger;
    }

    public static function debug(string $message, array $context = [])
    {
        self::getLogger()->debug($message, $context);
    }

    public static function notice(string $message, array $context = [])
    {
        self::getLogger()->notice($message, $context);
    }

    public static function warning(string $message, array $context = [])
    {
        self::getLogger()->warning($message, $context);
    }

    public static function error(string $message, array $context = [])
    {
        self::getLogger()->error($message, $context);
    }

    public static function info(string $message, array $context = [])
    {
        self::getLogger()->info($message, $context);
    }
}
