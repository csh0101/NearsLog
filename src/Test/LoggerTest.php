<?php

use PHPUnit\Framework\TestCase;
use NearsLog\Logger;
use NearsLog\LogLevel;

class NearsLoggerTest extends TestCase
{
    public function testLogMethod()
    {
        $bizName = 'MyBiz';
        $loggerName = 'MyLogger';
        $logFilePath = '/tmp/log_test.log';

        // 创建一个 Logger 实例
        $logger = new Logger($bizName,$loggerName, $logFilePath);

        // 调用 log 方法记录一条日志
        $message = 'Test log message';
        $level = LogLevel::INFO;
        $context = ['user_id' => 123,'user_name'=>'test'];
        $logger->log($level, $message, $context);

        // 在这里添加断言来验证日志是否成功记录，例如检查日志文件是否包含预期的内容
        // 检查日志文件是否包含了你刚才记录的日志内容
        $logContent = file_get_contents($logFilePath);
        $this->assertStringContainsString($message, $logContent);
        $this->assertStringContainsString($loggerName, $logContent);
        $this->assertStringContainsString($bizName, $logContent);
        // 更多可能的断言...
    }
}
