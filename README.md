# NearsLog
a Wrapper of MonoLog to record log for application and biz developer.```php

<?php
    public function testDefaultLogger()
    {
        $message = "a test log";
        $context = ["user" => "nears"];
        $logFilePath = "/tmp/biz.log";
        $bizName = "nencao";
        $loggerName = "anyname";
        Logger::setGlobalLogConfig($bizName, $loggerName, $logFilePath);
        Logger::Info($message, $context);
        // 更多可能的断言...
    }
```


