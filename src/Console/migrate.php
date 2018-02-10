#!/usr/bin/env php
<?php
require dirname(dirname(__DIR__)) . '/vendor/autoload.php';

use Symfony\Component\Console\Application;
use \Affinity4\Migrate\Console\Command;

$application = new Application();

$application->add(new Command\CreateMigrationCommand());

$application->run();
