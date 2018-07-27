<?php

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use FilippoToso\BotMan\Drivers\RocketChat\RocketChatAuth;


$app->get('/', function (\Slim\Http\Request $request, \Slim\Http\Response $response, array $args) {

    $hello = (new \lcidral\developstack\HelloWorld())->getHello();
    $world = (new \lcidral\developstack\HelloWorld())->getWorld();

    $client = new Predis\Client('tcp://redis:6379');
    $redis_value = $client->get('foo');

    $result = [
        'hello' => $hello,
        'world' => $world,
        'redis_value' => $redis_value
    ];

    $this->logger->info("pass '/' route");

    return $response->withJson($result);
});


$app->get('/hello', function ($request, $response)
{
    $helloWorld = (new \lcidral\developstack\HelloWorld())->getHelloWorld();
    $result = [
        'greeting' => $helloWorld
    ];

    $this->logger->info("pass '/hello' route");

    return $response->withJson($result);
});

$app->post('/mail', function ($request, $response)
{
    $result = $request->getParsedBody();

    $this->logger->info("pass '/mail' route");

    return $response->withJson($result);
});

$app->post('/rocket', function ($request, $response)
{

    $debug = [];

    $parsedBody = $request->getParsedBody();

    $debug['parseBody'] = $parsedBody;

    $auth = RocketChatAuth::getAuth(
        getenv('ROCKETCHAT_USERNAME'),
        getenv('ROCKETCHAT_PASSWORD'),
        'http://php-devstack-api_rocketchat_1:3000'
    );

    $debug['auth'] = $auth;

    $config = [
        // Your driver-specific configuration
        "rocketchat" => [
            "endpoint" => 'http://php-devstack-api_rocketchat_1:3000/hooks/Gj4nbNy4EnAKmCZhD/Hh3r6gBybb56jcACDHJfXfATcBHPath7xJupDkx3S775cp8u',
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
        //'auth' => $auth
    ];

    $debug['config'] = $config;

    DriverManager::loadDriver(\FilippoToso\BotMan\Drivers\RocketChat\RocketChatDriver::class);
    BotManFactory::create($config);
    $botman = BotManFactory::create($config);
    $response = $botman->say('Hello user', getenv('ROCKETCHAT_USER_ID'), \FilippoToso\BotMan\Drivers\RocketChat\RocketChatDriver::class);

    $debug['botman'] = $botman;
    $debug['response'] = $response;

    $debug = print_r($debug, 1);
    mail("test@example.com","Testing php -v ".phpversion(),$debug);
});
