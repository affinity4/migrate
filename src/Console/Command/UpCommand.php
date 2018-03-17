<?php
namespace Affinity4\Migrate\Console\Command;

use Affinity4\Migrate\File;
use Zend\Config\Reader\Json;
use Affinity4\Migrate\Adapter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * --------------------------------------------------
 * Up Command Class
 * --------------------------------------------------
 * 
 * @author Luke Watts <luke@affinity4.ie>
 * 
 * @since 0.0.1
 */
class UpCommand extends Command
{
    use \Affinity4\Migrate\Console\Command\OutputMessageTrait;
    use \Affinity4\Migrate\Console\Command\ConfigTrait;
    use \Affinity4\Migrate\Core\TimestampTrait;
    
    private $cwd;

    private $config;

    private $migration_dirs;

    /**
     * --------------------------------------------------
     * Configure
     * --------------------------------------------------
     * 
     * Configure the up command
     * 
     * @author Luke Watts <luke@affinity4.ie>
     * 
     * @since 0.0.1
     */
    protected function configure()
    {
        $this->cwd = getcwd();
        $this->config = (array) $this->getConfigAsArray();
        $this->migration_dirs = $this->getMigrationDirs($this->config);

        $this->setName('up')
            ->setDescription("Migrate up")
            ->addOption(
                'dry-run',
                null,
                InputOption::VALUE_OPTIONAL,
                'Will print out SQL to be run but will not run it',
                false
            )
            ->setHelp('This will run the up commands in the migration JSON files in your migrations folder');
    }

    /**
     * --------------------------------------------------
     * Get Migration Files From Migration Dir
     * --------------------------------------------------
     * 
     * Get an array of files from the migration_dir
     * 
     * @author Luke Watts <luke@affinity4.ie>
     * 
     * @since 0.0.1
     *
     * @param string $migrations_path
     * 
     * @return array
     */
    private function getMigrationFilesFromMigrationDir($migrations_path)
    {
        return array_filter(scandir($migrations_path), function($file) {
            return ($file !== '.' && $file !== '..');
        });
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dry_run = ($input->getOption('dry-run') === null) ? true : false;

        // Use scandir on the migrations folder for files matching the filepattern for migration files
        $migrations_path = $this->cwd . DIRECTORY_SEPARATOR . $this->migration_dirs;
        if ($scandir = @scandir($migrations_path) !== false) {
            $migration_files = $this->getMigrationFilesFromMigrationDir($migrations_path);
    
            if (!empty($migration_files)) {
                $files = array_map(
                    function ($file) use ($migrations_path) {
                        return $migrations_path . DIRECTORY_SEPARATOR . $file;
                    }, 
                    $migration_files
                );

                $Json = new Json();
                $migrations = [];
                foreach ($files as $file) {
                    $path_split = explode(DIRECTORY_SEPARATOR, $file);  
                    $filename = array_pop($path_split);
                    $migrations[$filename] = $Json->fromFile($file);
                }

                // Get dialect from config or use default
                $dialect = (array_key_exists('dialect', $this->config) && !empty($this->config['dialect'])) 
                    ? $this->config['dialect']
                    : 'mysql';

                $db_config = (array_key_exists('database', $this->config) && !empty($this->config['database'])) 
                    ? (array) $this->config['database']
                    : [];

                if ($dialect === 'mysql') {
                    $Db = new Adapter\Mysql($db_config);
                }
                
                
                // Run each up sequence in order
                foreach ($migrations as $filename => $migration) {
                    if (array_key_exists('create', $migration['up'])) {
                        $Db->createTable($migration['up']['create'], $dry_run);
                    }
                }
    
                // Append files to $lines array

                $message = ($dry_run) ? 'Dry Run. No SQL executed!' :'Migrated up!';
            } else {
                $message = 'You do not have any migration files!';
            }
        } else {
            $message = 'No such migrations directory at ' . $migrations_path . '! Have you run the migrate init command?';
        }

        $lines = [
            $message
        ];

        $this->outputMessage($lines, $output);
    }
}