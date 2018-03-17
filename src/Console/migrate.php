#!/usr/bin/env php
<?php
require dirname(dirname(__DIR__)) . '/vendor/autoload.php';

use Symfony\Component\Console\Application;
use \Affinity4\Migrate\Console\Command;

$application = new Application();

$commandClasses = array_map(
    function ($command) {
        return str_replace('.php', '', $command);
    }, 
    array_filter(
        scandir(__DIR__ . '/Command'), 
        function($file) {
            return (preg_match('/^.*Command\.php/', $file) === 1);
        }
    )
);

foreach ($commandClasses as $commandClass) {
    $class = '\\Affinity4\\Migrate\\Console\\Command\\' . $commandClass;
    if (class_exists('\\Affinity4\\Migrate\\Console\\Command\\' . $commandClass)) {
        $application->add(new $class());
    } else {
        $error = sprintf(
            'Class %s not found in %s on line %s. Make sure the file exists and the composer autoloader is up to date.', 
            $class, 
            __FILE__, 
            __LINE__
        );

        throw new \NotFoundException($error);
    }
}

$application->run();
