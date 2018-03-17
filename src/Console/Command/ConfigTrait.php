<?php
namespace Affinity4\Migrate\Console\Command;

/**
 * --------------------------------------------------
 * ConfigTrait
 * --------------------------------------------------
 * 
 * Trait uses to get config array and values from 
 * JSON config file(s)
 * 
 * @author Luke Watts <luke@affinity4.ie>
 * 
 * @since 0.0.1
 */
trait ConfigTrait
{
    /**
     * --------------------------------------------------
     * Get Config As Array
     * --------------------------------------------------
     * 
     * Gets json file and returns as php array
     * 
     * @author Luke Watts <luke@affinity4.ie>
     * 
     * @since 0.0.1
     */
    public function getConfigAsArray()
    {
        $cwd = getcwd();
        $path_to_config = $cwd . '/migrate.json';

        $contents = file_get_contents($path_to_config);

        return json_decode($contents);
    }

    /**
     * --------------------------------------------------
     * Get Migration Dirs
     * --------------------------------------------------
     * 
     * Get migration directory|directories from config
     * 
     * @author Luke Watts <luke@affinity4.ie>
     * 
     * @since 0.0.1
     * 
     * @param array $config
     */
    public function getMigrationDirs(array $config)
    {
        $migrations_dir = 'migrations';

        if (array_key_exists('migration_dirs', $config) && !empty($config['migration_dirs'])) {
            if (is_array($config['migration_dirs'])) {
                echo 'This feature is not built yet :(';
                // TODO: Build this to allow option for dev/prod migrations to be separate
            } else {
                $migrations_dir = $config['migration_dirs'];
            }
        }

        return $migrations_dir;
    }
}