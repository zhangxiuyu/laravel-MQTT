#!/usr/bin/env php
<?php
ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');

error_reporting(E_ALL);

require __DIR__.'/../../vendor/autoload.php';


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