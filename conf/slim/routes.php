<?php

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use FilippoToso\BotMan\Drivers\RocketChat\RocketChatAuth;


$app->get('/', function (\Slim\Http\Request $request, \Slim\Http\Response $response, array $args) {

    $hello = (new \lcidral\developstack\HelloWorld())->getHello();
    $world = (new \lcidral\developstack\HelloWorld())->getWorld();

    $result = [
        'hello' => $hello,
        'world' => $world
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

    if ($parsedBody['text'] == 'bot help') {
        $output = "
        ```
- bot help - mostra ajuda
- bot hora - mostra hora
- bot data - mostra data
```
        ";
        $response = $botman->say($output, getenv('ROCKETCHAT_USER_ID'), \FilippoToso\BotMan\Drivers\RocketChat\RocketChatDriver::class);
        $debug['response'] = $response;
    }

    $commands = [];
    $commands['bot'] = [
        'time' => date('H:i:s'),
        'date' => date('d/m/y'),
        'restart:memcache' => 'command to restart memcache',
        'restart:redis' => 'command to restart redis',
        'restart:supervisor' => 'command to restart supervisor',
        'build:app' => 'command to build app',
        'status:supervisor' => 'command to show status from supervisor',
        'run:acceptance:test' => 'command to run acceptance tests',
        'run:functional:test' => 'command to run functional tests',
        'run:unit:test' => 'command to run unit tests',
    ];

    $explode = explode( " ", $parsedBody['text'] );

    $output = $commands[$explode[0]][$explode[1]];

    $parameters = [
        'username' => 'Bot User',
        'icon_url' => 'https://cdn2.vectorstock.com/i/1000x1000/29/11/ice-mountain-icon-flat-style-vector-19372911.jpg',
    ];

    $response = $botman->say($output, getenv('ROCKETCHAT_USER_ID'), \FilippoToso\BotMan\Drivers\RocketChat\RocketChatDriver::class, $parameters);

    $debug['botman'] = $botman;
    $debug['response'] = $response;

    mail("rocket@" . getenv('VIRTUAL_HOST_API72'),"Debug PHP: " . phpversion(), print_r( $debug, 1) );

});
