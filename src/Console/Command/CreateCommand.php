<?php
namespace Affinity4\Migrate\Console\Command;

use Affinity4\Migrate\File;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class CreateCommand extends Command
{
    use \Affinity4\Migrate\Console\Command\OutputMessageTrait;
    use \Affinity4\Migrate\Console\Command\ConfigTrait;
    use \Affinity4\Migrate\Core\TimestampTrait;

    private $split;

    private $action;

    private $config;

    private $migration_dirs;

    private $cwd;

    private function setSplit($name)
    {
        $this->split = explode('::', $name);
    }

    private function setAction($name)
    {
        $this->action = array_shift($this->split);
    }

    protected function configure()
    {
        $this->cwd = getcwd();
        $this->config = (array) $this->getConfigAsArray();
        $this->migration_dirs = $this->getMigrationDirs($this->config);

        $this->setName('create')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the migration e.g. create_table_users')
            ->addArgument('config', InputArgument::OPTIONAL, 'The location of the migrate.json config file')
            ->setDescription("Create a migration file")
            ->setHelp('This will generate a migration JSON file in your migrations folder');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $config = $input->getArgument('config');

        $this->setSplit($name);
        $this->setAction($name);

        $timezone = (array_key_exists('timezone', $this->config) && !empty($this->config['timezone'])) 
            ? $this->config['timezone']
            : 'Europe/Dublin';
        
        $name = str_replace(':', '_', $name);
        $sep = DIRECTORY_SEPARATOR;
        $dir = $this->cwd . $sep . $this->migration_dirs;
        $timestamp = $this->migrationTimestamp($timezone);
        $filename = $timestamp . '_' . $name . '.json';
        $migration_file_path = $dir . $sep . $filename;

        if (File\Factory::create($name, $migration_file_path) !== false) {
            $lines = [
                "Migration created!",
                "File: $filename",
                "Directory: $dir"
            ];
        } else {
            $lines = [
                "Migration could not be created!",
                "File: $filename",
                "Directory: $dir"
            ];
        }

        $this->outputMessage($lines, $output);
    }
}