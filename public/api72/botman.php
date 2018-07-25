<?php

require __DIR__ . '/../../vendor/autoload.php';

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;

$config = [
    // Your driver-specific configuration
     "rocketchat" => [
        "token" => getenv('ROCKET_CHAT_TOKEN')
     ]
];
DriverManager::loadDriver(\FilippoToso\BotMan\Drivers\RocketChat\RocketChatDriver::class);
BotManFactory::create($config);
$botman = BotManFactory::create($config);
$botman->hears('hello', function (BotMan $bot) {
    $bot->reply('Hello yourself.');
});
$botman->listen();
