<?php
namespace Affinity4\Migrate\Console\Command;

use Affinity4\Migrate\File;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class CreateMigrationCommand extends Command
{
    use \Affinity4\Migrate\Console\Command\ConfigTrait;

    private $split;

    private $action;

    private $config;

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
        $this->setName('create:migration')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the migration e.g. create_table_users')
            ->addArgument('config', InputArgument::OPTIONAL, 'The location of the migrate.json config file')
            ->setDescription("Create a migration file")
            ->setHelp('This will generate a migration JSON file in your migrations folder');
    }

    private function outputMessage($lines, $output)
    {
        $max = 0;
        foreach ($lines as $line) {
            $count = strlen($line);

            if ($max <= $count) {
                $max = $count;
            }
        }

        $rule = '';
        for ($n = 0; $n < $max; $n++) {
            $rule .= '-';
        }

        array_splice($lines, 0, 0, $rule);
        array_splice($lines, 2, 0, $rule);

        $output->writeln($lines);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $config = $input->getArgument('config');

        $this->setSplit($name);
        $this->setAction($name);

        $cwd = getcwd();
        $this->config = (array) $this->getConfigAsArray($cwd . '/migrate.json');

        $migration_dir = 'migrations';

        if (array_key_exists('migration_dirs', $this->config) && !empty($this->config['migration_dirs'])) {
            if (is_array($this->config['migration_dirs'])) {
                echo 'This feature is not built yet :(';
                // TODO: Build this to allow option for dev/prod migrations to be separate
            } else {
                $migration_dir = $this->config['migration_dirs'];
            }
        }
        
        $name = str_replace(':', '_', $name);
        $sep = DIRECTORY_SEPARATOR;
        $dir = $cwd . $sep . $migration_dir;
        $timestamp = date('YmdHis');
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