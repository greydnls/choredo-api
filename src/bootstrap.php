<?php

use Zend\Diactoros\Response;
use Choredo\Providers;

require_once __DIR__ . '/../vendor/autoload.php';

try {
    (new Dotenv\Dotenv(__DIR__ . '/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

$app = new \Choredo\App();

return $app;
