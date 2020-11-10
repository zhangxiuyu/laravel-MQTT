<?php

require '../../../vendor/autoload.php';

$server = '192.168.1.123';     // change if necessary
$port = 1883;                     // change if necessary
$username = '';                   // set your username
$password = '';                   // set your password
$client_id = uniqid(); // make sure this is unique for connecting to sever - you could use uniqid()

$mqtt = new Bluerhinos\phpMQTT($server, $port, $client_id);

if ($mqtt->connect(true, NULL, $username, $password)) {
    $mqtt->publish('123', 'Hello World! at ' . date('r'), 0, true);
    $mqtt->close();
    echo " 成功!\n";
} else {
    echo "Time out!\n";
}