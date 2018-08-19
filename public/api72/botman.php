<?php

error_log('not work');

require __DIR__ . '/../../vendor/autoload.php';

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use FilippoToso\BotMan\Drivers\RocketChat\RocketChatAuth;

$auth = RocketChatAuth::getAuth(
    getenv('ROCKETCHAT_USERNAME'),
    getenv('ROCKETCHAT_PASSWORD'),
    'http://rocketchat:3000'
);

$config = [
    // Your driver-specific configuration
    "rocketchat" => [
        "endpoint" => 'http://rocketchat:3000/hooks/' . getenv('ROCKETCHAT_TOKEN'),
        "token" => getenv('ROCKETCHAT_OUTGOING_TOKEN')
    ],
    'user_id' =>  getenv('ROCKETCHAT_USER_ID'),
    'bot' => [
        'username' => getenv('ROCKETCHAT_USERNAME'),
        'password' => getenv('ROCKETCHAT_PASSWORD'),
    ],
    'matchingKeys' => [
        'user_id', 'user_name', 'channel_id', 'text', 'message_id', 'timestamp', 'bot',
    ],
    'auth' => $auth
];

DriverManager::loadDriver(\FilippoToso\BotMan\Drivers\RocketChat\RocketChatDriver::class);
BotManFactory::create($config);

$botman = BotManFactory::create($config);

$botman->hears('hello', function($bot) {
    $bot->reply('Hello!');
    $bot->ask('Whats your name?', function($answer, $bot) {
        $bot->say('Welcome '.$answer->getText());
    });
});

$botman->group(['driver' => \FilippoToso\BotMan\Drivers\RocketChat\RocketChatDriver::class], function($bot) {
    $bot->hears('keyword', function($bot) {
        $bot->say('Welcome 123');
    });
});

$botman->listen();

mail("rocket@" . getenv('VIRTUAL_HOST_API72'),"botman.php Debug : " . phpversion(), print_r( $botman, 1) );

