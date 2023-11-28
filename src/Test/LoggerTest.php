<?php

use PHPUnit\Framework\TestCase;
use NearsLog\Logger;

class NearsLoggerTest extends TestCase
{

    public function testDefaultLogger()
    {


        $message = "a test log";
        $context = ["user" => "nears"];
        $filePath = "/tmp/biz.log";
        $bizName = "default";
        $loggerName = "default";
        global $_NEARS_LOG_CONFIG;
        $_NEARS_LOG_CONFIG = [];
        $_NEARS_LOG_CONFIG["bizName"] = $bizName;
        $_NEARS_LOG_CONFIG["loggerName"] = $loggerName;
        $_NEARS_LOG_CONFIG["filePath"] = $filePath;
        $_NEARS_LOG_CONFIG["requestID"] = bin2hex(random_bytes(16));
        $_NEARS_LOG_CONFIG["logLevel"] = 200;
        Logger::Info($message, $context);

        $logContent = file_get_contents($filePath);
        $this->assertStringContainsString($message, $logContent);
        $this->assertStringContainsString($bizName, $logContent);
        $this->assertStringContainsString($loggerName, $logContent);
        // 更多可能的断言...
    }
}
