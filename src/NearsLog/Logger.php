<?php

namespace NearsLog;

use Monolog\Formatter\JsonFormatter;
use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;

use Monolog\ErrorHandler;


class LogLevel
{
    public const DEBUG = 100;
    public const INFO = 200;
}

class Logger
{
    protected $logger;
    
    protected $bizName;


    public function __construct($bizName="application",$loggerName, $logFilePath, $logLevel = MonologLogger::DEBUG)
    {
        $this->bizName = $bizName;
        $this->logger = new MonologLogger($loggerName);
        $formatter = new JsonFormatter();
        $file_handler = new StreamHandler($logFilePath, $logLevel);
        $file_handler->setFormatter($formatter);
        ErrorHandler::register($this->logger, [], [], null); 
        $this->logger->pushHandler($file_handler);
        $this->logger->pushProcessor(function ($record) {
            $record['extra']['biz_name'] = $this->bizName;
            return $record;
        });
        
    }
    public function log(int $level, string $message, array $context = [])
    {
        $this->logger->log($level, $message, $context);
    }
}
