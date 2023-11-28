<?php

namespace NearsLog;

use Monolog\Formatter\JsonFormatter;
use Monolog\Logger as MonoLogger;
use Monolog\Handler\RotatingFileHandler;

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

    // loggerInstance logger instance
    private static $loggerInstance;
    // loggerName is recommended to use the name of the env, such as net,com,ext,prod
    private static $loggerName;
    // logFilePath is the file where the logs output write into,the defaul path is /log/biz.log
    private static $logFilePath;

    private static $logLevel = MonoLogger::INFO;


    private function __construct()
    {
    }


    // GetGlobalRequestID get global request id
    public static function getGlobalRequestID()
    {
        global $_NEARS_LOG_CONFIG;
        return $_NEARS_LOG_CONFIG["requestID"];
    }

    // GetGlobalRequestID set global request id
    // it recommend to use SetGlobalRequestID in the php entrypoint file, such as index.php
    public static function setGlobalRequestID($requestID)
    {

        global $_External_System_Request_ID;
        $_External_System_Request_ID = $requestID;
    }


    public static function setGlobalLogConfig(string $bizName, string $loggerName, string $filePath, int $logLevel = MonoLogger::INFO)
    {
        global $_NEARS_LOG_CONFIG;
        $_NEARS_LOG_CONFIG["bizName"] = $bizName;
        $_NEARS_LOG_CONFIG["loggerName"] = $loggerName;
        $_NEARS_LOG_CONFIG["filePath"] = $filePath;
        $_NEARS_LOG_CONFIG["logLevel"] = $logLevel;
        $_NEARS_LOG_CONFIG["requestID"] = bin2hex(random_bytes(16));
    }

    private static function getBizname()
    {

        global $_NEARS_LOG_CONFIG;
        return $_NEARS_LOG_CONFIG["bizName"];
    }

    private static function getRequestID()
    {

        global $_NEARS_LOG_CONFIG;
        global $_External_System_Request_ID;
        if ($_External_System_Request_ID != null) {
            return $_External_System_Request_ID;
        }
        return $_NEARS_LOG_CONFIG["requestID"];
    }

    private static function getGlobalLogConfig()
    {
        global $_NEARS_LOG_CONFIG;

        self::$loggerName = $_NEARS_LOG_CONFIG["loggerName"];
        self::$logFilePath = $_NEARS_LOG_CONFIG["filePath"];
        self::$logLevel = $_NEARS_LOG_CONFIG["logLevel"];
    }

    public static function getLogFilePath()
    {
        return self::$logFilePath;
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
        self::getGlobalLogConfig();
        $logger = new MonoLogger(self::$loggerName);
        $formatter = new JsonFormatter();
        $file_handler = new RotatingFileHandler(self::$logFilePath, 2, self::$logLevel);
        $file_handler->setFormatter($formatter);
        $logger->pushHandler($file_handler);
        $logger->pushProcessor(function ($record) {
            $record['extra']['biz_name'] = self::getbizName();
            return $record;
        });
        return $logger;
    }


    public static function debug(string $message, array $context = [])
    {
        $context["request_id"] = self::getRequestID();
        self::getLogger()->debug($message, $context);
    }

    public static function notice(string $message, array $context = [])
    {

        $context["request_id"] = self::getRequestID();
        self::getLogger()->notice($message, $context);
    }

    public static function warning(string $message, array $context = [])
    {

        $context["request_id"] = self::getRequestID();
        self::getLogger()->warning($message, $context);
    }

    public static function error(string $message, array $context = [])
    {

        $context["request_id"] = self::getRequestID();
        self::getLogger()->error($message, $context);
    }

    public static function info(string $message, array $context = [])
    {
        $context["request_id"] = self::getRequestID();
        self::getLogger()->info($message, $context);
    }

    public static function exception(string $message, $context = [])
    {
        $context["request_id"] = self::getRequestID();
        $context["exception"] = true;
        self::getLogger()->error($message, $context);
    }
}
