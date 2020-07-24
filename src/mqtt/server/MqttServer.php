<?php

namespace Mqtt\server;

use Mqtt\AppServer;
use Swoole\Server;


class MqttServer
{

    protected $_server;

    protected $_config;

    /**
     * Server constructor.
     */
    public function __construct($config)
    {

        $this->_config = $config;

        $this->_server = new Server($config['ip'], $config['port']);
        $this->_server->set($config['settings']);

        $this->_server->on('Start', [$this, 'onStart']);
        $this->_server->on('Receive', [$this, 'onReceive']);
        foreach ($config['callbacks'] as $eventKey => $callbackItem) {
            [$class, $func] = $callbackItem;
            $this->_server->on($eventKey, [$class, $func]);
        }
        $this->_server->start();
    }

    public function onStart()
    {
        AppServer::echoSuccess("Swoole MQTT Server running：mqtt://{$this->_config['ip']}:{$this->_config['port']}");
    }


    public function onReceive($server, $fd, $fromId, $data)
    {
        try {
            $data = MQTT::decode($data);
            if (is_array($data) && isset($data['cmd'])) {
                switch ($data['cmd']) {
                    case MQTT::PINGREQ: // 心跳请求
                        [$class, $func] = $this->_config['receiveCallbacks'][MQTT::PINGREQ];
                        $obj = new $class();
                        if ($obj->{$func}($server, $fd, $fromId, $data)) {
                            // 返回心跳响应
                            $server->send($fd, MQTT::getAck(['cmd' => 13]));
                        }
                        break;
                    case MQTT::DISCONNECT: // 客户端断开连接
                        [$class, $func] = $this->_config['receiveCallbacks'][MQTT::DISCONNECT];
                        $obj = new $class();
                        if ($obj->{$func}($server, $fd, $fromId, $data)) {
                            if ($server->exist($fd)) {
                                $server->close($fd);
                            }
                        }
                        break;
                    case MQTT::CONNECT: // 连接
                    case MQTT::PUBLISH: // 发布消息
                    case MQTT::SUBSCRIBE: // 订阅
                    case MQTT::UNSUBSCRIBE: // 取消订阅
                        [$class, $func] = $this->_config['receiveCallbacks'][$data['cmd']];
                        $obj = new $class();
                        $obj->{$func}($server, $fd, $fromId, $data);
                        break;
                }
            } else {
                $server->close($fd);
            }
        } catch (\Exception $e) {
            $server->close($fd);
        }
    }
}