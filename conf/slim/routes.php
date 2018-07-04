<?php
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
