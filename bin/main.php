<?php

use Angorb\HueCli\Cli;
use Angorb\HueCli\Environment;

require_once __DIR__ . '/../vendor/autoload.php';

error_reporting(E_ALL ^ E_DEPRECATED);
$env = new Environment();
new Cli($env);
