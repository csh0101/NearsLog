<?php

use PHPUnit\Framework\TestCase;
use NearsLog\Logger;

class NearsLoggerTest extends TestCase
{

    public function testDefaultLogger()
    {
        $message = "a test log";
        $context = ["user" => "nears"];
        $logFilePath = "/tmp/biz.log";
        $bizName = "default";
        $loggerName = "default";

        Logger::setGlobalLogConfig($bizName, $loggerName, $logFilePath);
        Logger::Info($message, $context);

        $logContent = file_get_contents($logFilePath);
        $this->assertStringContainsString($message, $logContent);
        $this->assertStringContainsString($bizName, $logContent);
        $this->assertStringContainsString($loggerName, $logContent);
        // 更多可能的断言...
    }
}
