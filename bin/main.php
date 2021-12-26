<?php

use Angorb\HueCli\Cli;

error_reporting(E_ALL && ~E_DEPRECATED);

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../config');
$dotenv->load();
$dotenv->required('HUE_HUB_IP')->allowedRegexValues('/([0-9\.]+){7,15}/'); // TODO better
$dotenv->required('HUE_HUB_TOKEN')->allowedRegexValues('/([\d\w]{40})/'); // TODO generate other tokerns to check

new Cli($_ENV['HUE_HUB_IP'], $_ENV['HUE_HUB_TOKEN']);
