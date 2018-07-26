<?php
require __DIR__ . '/../../vendor/autoload.php';

define('C3_CODECOVERAGE_ERROR_LOG_FILE', '/logs/c3_error.log'); //Optional (if not set the default c3 output dir will be used)
include __DIR__ . '/../../c3.php';

echo "Hello World";

// Same set of parameters, passed using an URI string:
$client = new Predis\Client('tcp://redis:6379');
$client->set('foo', 'helllowwww');

?>

<img src="/img/saochico.png">
