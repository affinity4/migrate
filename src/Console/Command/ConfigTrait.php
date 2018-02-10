<?php
namespace Affinity4\Migrate\Console\Command;

trait ConfigTrait
{
    public function getConfigAsArray($path_to_config)
    {
        $contents = file_get_contents($path_to_config);

        return json_decode($contents);
    }
}