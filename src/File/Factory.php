<?php
namespace Affinity4\Migrate\File;

class Factory
{
    public static function createTable($table = '')
    {
        return [
            'up' => [
                'create' => [
                    'table' => $table,
                    'columns' => [

                    ]
                ]
            ],
            'down' => [
                'drop' => [
                    'table' => $table
                ]
            ]
        ];
    }

    public static function alterTable($action, $rename_from = '', $rename_to = '')
    {
        switch ($action) {
            case 'rename' :
                return [
                    'up' => [
                        'alter-table' => [
                            'rename' => [
                                'from' => $rename_from,
                                'to' => $rename_to
                            ]
                        ]
                    ],
                    'down' => [
                        'alter-table' => [
                            'rename' => [
                                'from' => $rename_to,
                                'to' => $rename_from
                            ]
                        ]
                    ]
                ];
            break;
            case 'add-column' :
                return [
                    'up' => [
                        'add-columns' => [
                            'column1' => '',
                            'column2' => ''
                        ]
                    ],
                    'down' => [
                        'add-columns' => [
                            'column1' => '',
                            'column2' => ''
                        ]
                    ],
                ];
            break;
            default:
                return [
                    'up' => [
                        'add-columns' => [
                            'column1' => '',
                            'column2' => ''
                        ]
                    ],
                    'down' => [
                        'add-columns' => [
                            'column1' => '',
                            'column2' => ''
                        ]
                    ],
                ];
            break;
        }
    }

    public static function create($command, $migration_file_path)
    {
        $tables = explode('__', $command);
            
        if (count($tables) === 3) {
            $rename_from = $tables[1];
            $rename_to = $tables[2];
        } else {
            $rename_from = '';
            $rename_to = '';
        }

        $actions = explode('_', $command);
        switch ($actions[0]) {
            case 'alter-table' :
                $migration = self::alterTable($actions[1], $rename_from, $rename_to);
            break;
            case 'create-table' :
                $migration = self::createTable($actions[2]);
            break;
            default :
                $migration = self::createTable();
            break;
        } 

        $migration_file_content = json_encode($migration, \JSON_PRETTY_PRINT);

        return file_put_contents($migration_file_path, $migration_file_content);
    }
}