<?php


namespace Mqtt;

use Mqtt\server\MQTT;
use Mqtt\server\MqttServer;

class AppServer
{
    /**
     * @var string
     */
    protected static $version = '1.0.3';

    public static function welcome()
    {
        $appVersion = self::$version;
        echo <<<EOL
            _              
           | |             
  _ __ ___ | |_ __ _  __ _ 
 | '_ ` _ \| __/ _` |/ _` |
 | | | | | | || (_| | (_| |
 |_| |_| |_|\__\__, |\__, |
                  | |   | |
                  |_|   |_|       Version: {$appVersion}

EOL;
    }


    public static function println($strings)
    {
        echo $strings . PHP_EOL;
    }

    public static function echoSuccess($msg)
    {
        self::println('[' . date('Y-m-d H:i:s') . '] [INFO] ' . "\033[32m{$msg}\033[0m");
    }

    public static function echoError($msg)
    {
        self::println('[' . date('Y-m-d H:i:s') . '] [ERROR] ' . "\033[31m{$msg}\033[0m");
    }

    public static function run(array $config = [])
    {
        if (empty($config)) return exit(self::echoError('config is empty'));
        self::welcome();
        global $argv;
        $count = count($argv);
        $funcName = $argv[$count - 1];
        $command = explode(':', $funcName);

        switch ($command[0]) {
            case 'mqtt':
                $className = MqttServer::class;
                break;
            default:
                exit(self::echoError("command {$command[0]} is not exist, you can use {$argv[0]} mqtt:start"));
        }
        switch ($command[1]) {
            case 'start':
                new $className($config);
                break;
            default:
                self::echoError("use {$argv[0]} mqtt:start");
        }
    }
}