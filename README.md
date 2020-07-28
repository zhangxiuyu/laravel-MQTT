# laravel-MQTT

[![Simps License](https://poser.pugx.org/simple-swoole/simps/license)](file:///C:/PHP/MQTT/vendor/simple-swoole/simps/LICENSE)
[![PHP Version](https://img.shields.io/badge/php-%3E=7.1-brightgreen.svg)](https://www.php.net/) 
[![Swoole Version](https://img.shields.io/badge/swoole-%3E=4.4.0-brightgreen.svg)](https://github.com/swoole/swoole-src)



### 使用样例:

```php
$config = [
    'ip' => '0.0.0.0',
    'port' => 8080,
    'callbacks' => [
    ],
    'receiveCallbacks' => [
        \Mqtt\server\MQTT::CONNECT => [\Mqtt\MqttServer::class, 'onMqConnect'],
        \Mqtt\server\MQTT::PINGREQ => [\Mqtt\MqttServer::class, 'onMqPingreq'],
        \Mqtt\server\MQTT::DISCONNECT => [\Mqtt\MqttServer::class, 'onMqDisconnect'],
        \Mqtt\server\MQTT::PUBLISH => [\Mqtt\MqttServer::class, 'onMqPublish'],
        \Mqtt\server\MQTT::SUBSCRIBE => [\Mqtt\MqttServer::class, 'onMqSubscribe'],
        \Mqtt\server\MQTT::UNSUBSCRIBE => [\Mqtt\MqttServer::class, 'onMqUnsubscribe'],
    ],
    'settings' => [
        'worker_num' => 1,
        'open_mqtt_protocol' => true,
    ],
];

Mqtt\AppServer::run($config);
```

### 效果:

```c
root@5ef6b759adf0:/var/www/PHP/laravel-MQTT/src/mqtt# php Test.php mqtt:start
                  _   _   
                 | | | |  
  _ __ ___   __ _| |_| |_ 
 | '_ ` _ \ / _` | __| __|
 | | | | | | (_| | |_| |_ 
 |_| |_| |_|\__, |\__|\__|
               | |        
               |_|           Version: 1.0.3
[2020-07-24 17:20:40] [INFO] Swoole MQTT Server running：mqtt://0.0.0.0:8080

```

