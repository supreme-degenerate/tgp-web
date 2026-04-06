<?php

use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/../', '.env.test');
$dotenv->load();

Tester\Environment::setup();

date_default_timezone_set('Europe/Prague');
define('TmpDir', '/tmp/app-tests');
