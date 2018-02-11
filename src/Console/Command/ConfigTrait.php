<?php
namespace Affinity4\Migrate\Console\Command;

trait ConfigTrait
{
    public function getConfigAsArray()
    {
        $cwd = getcwd();
        $path_to_config = $cwd . '/migrate.json';

        $contents = file_get_contents($path_to_config);

        return json_decode($contents);
    }

    public function setMigrationsDir(array $config)
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