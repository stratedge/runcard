<?php

$autoloader = require __DIR__ . '/../src/autoload.php';

if (!$autoloader()) {
    die('uh-oh');
}

$app = new Stratedge\Runcard\Console\Application();
$app->run();