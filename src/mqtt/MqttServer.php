<?php


namespace Mqtt;

use Mqtt\server\MQTT;
use Mqtt\server\MqttInterface;

class MqttServer implements MqttInterface
{


    /*
     *
    Reserved 0 禁止 保留
    CONNECT  1 客户端到服务端 客户端请求连接服务端
    CONNACK  2 服务端到客户端 连接报文确认
    PUBLISH  3 两个方向都允许 发布消息
    PUBACK   4 两个方向都允许 QoS 1消息发布收到确认
    PUBREC   5 两个方向都允许 发布收到（保证交付第一步）
    PUBREL   6 两个方向都允许 发布释放（保证交付第二步）
    PUBCOMP  7 两个方向都允许 QoS 2消息发布完成（保证交互第三步）
    SUBSCRIBE   8 客户端到服务端 客户端订阅请求
    SUBACK      9 服务端到客户端 订阅请求 报文确认
    UNSUBSCRIBE 10 客户端到服务端 客户端取消订阅请求
    UNSUBACK    11 服务端到客户端 取消订阅报文确认
    PINGREQ     12 客户端到服务端 心跳请求
    PINGRESP    13 服务端到客户端 心跳响应
    DISCONNECT  14 客户端到服务端 客户端断开连接
    Reserved    15 禁止 保留

    相关文档说明
    swoole文档
    https://wiki.swoole.com/#/server/events?id=onreceive

    simps框架文档
    https://simps.io/#/zh-cn/mqtt/server

    mqtt文档
    https://mcxiaoke.gitbook.io/mqtt/03-controlpackets/0303-publish
     */


    public function onMqConnect($server, int $fd, $fromId, $data)
    {
        // 如果协议名不正确服务端可以断开客户端的连接，也可以按照某些其它规范继续处理CONNECT报文
        if ($data['protocol_name'] != "MQTT") {
            $server->close($fd);
            return false;
        }
        // 判断客户端是否已经连接，如果是需要断开旧的连接
        // 判断是否有遗嘱信息
        // ...

        // 返回确认连接请求
        $server->send(
            $fd,
            MQTT::getAck(
                [
                    'cmd' => 2, // CONNACK固定值为2
                    'code' => 0, // 连接返回码 0表示连接已被服务端接受
                    'session_present' => 0
                ]
            )
        );

    }



    public function onMqDisconnect($server, int $fd, $fromId, $data): bool
    {
        return true;
        // TODO: Implement onMqDisconnect() method.
    }
    public function onMqPingreq($server, int $fd, $fromId, $data): bool
    {
        return true;
        // TODO: Implement onMqPingreq() method.
    }
    public function onMqPublish($server, int $fd, $fromId, $data)
    {
//        $this->log(6);
        // TODO: Implement onMqPublish() method.
        echo "发送成功！";
        $server->send(
            $fd,
            MQTT::getAck(
                [
                    'cmd' => 3,
                    'topic' => $data['topic'],
                    'message_id' => $data['message_id'] ??'',
                    'content' => $data['content'],
                ]
            )
        );

    }
    public function onMqSubscribe($server, int $fd, $fromId, $data)
    {
        // TODO: Implement onMqSubscribe() method.
//        data {"cmd":8,"message_id":1,"topics":{"aaa":0}}

        $payload = [];
        foreach ($data['topics'] as $K => $v){
            if (is_numeric($v) && $v < 3 ){
                $payload[] = chr($v);
            }else{
                $payload[] = chr(0x80);
            }
        }

        $server->send(
            $fd,
            MQTT::getAck(
                [
                    'cmd' => 9,
                    'message_id' => $data['message_id'] ??'', // 连接返回码 0表示连接已被服务端接受
                    'payload' => $payload
                ]
            )
        );
        echo "订阅成功";
    }
    public function onMqUnsubscribe($server, int $fd, $fromId, $data)
    {
        // TODO: Implement onMqUnsubscribe() method.
        echo "取消订阅";

        $payload = [];
        foreach ($data['topics'] as $K => $v){
            if (is_numeric($v) && $v < 3 ){
                $payload[] = chr($v);
            }else{
                $payload[] = chr(0x80);
            }
        }
        $server->send(
            $fd,
            MQTT::getAck(
                [
                    'cmd' => 11,
                    'message_id' => $data['message_id'] ??'',
                    'payload' => $payload
                ]
            )
        );

    }

}