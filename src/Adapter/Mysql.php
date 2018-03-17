<?php
namespace Affinity4\Migrate\Adapter;

use Affinity4\Migrate\Core\Dbo;

class Mysql extends Dbo
{
    protected $config;

    public function __construct(array $config = [])
    {
        $config['driver'] = 'mysql';

        parent::__construct($config);

        $this->config = $this->getConfig();
    }

    public function createTable($create, $dry_run = false)
    {
        $table = $create['table'];

        $if_not_exists = (
            array_key_exists('if not exists', $create) && $create['if not exists'] !== false
            ||
            !array_key_exists('if not exists', $create)
        ) ? 'IF NOT EXISTS ' : '';
        
        $sql = sprintf("CREATE TABLE %s`%s`.`%s` (%s", $if_not_exists, $this->config['name'], $table, PHP_EOL);
        $columns = array_map(function ($column, $attrs) {
            return sprintf('    `%s` %s', $column, $attrs);
        }, array_keys($create['columns']), array_values($create['columns']));

        $sql .= implode(',' . PHP_EOL, $columns) . PHP_EOL;
        $sql .= ')' . PHP_EOL;

        if (!$dry_run) {
            try {
                $Dbo = self::connect();
    
                $Dbo->beginTransaction();
    
                self::connect()->query($sql);
    
                $Dbo->commit();
    
                return true;
            } catch (\PDOException $e) {
                $Dbo->rollBack();
                
                return false;
            }
        } else {
            echo $sql . "\n";
        }
    }

    public function alterTable($alter)
    {
        var_dump($alter);
    }
}