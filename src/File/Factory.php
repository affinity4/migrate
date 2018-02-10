<?php
namespace Affinity4\Migrate\File;

class Factory
{
    public static function createTable()
    {
        return [
            'up' => [
                'create' => [
                    'table' => 'users',
                    'columns' => [
                        'id' => 'int unsigned not null primary key auto_increment',
                        'created' => 'datetime not null default 0',
                        'modified' => 'datetime not null on update current_timestamp default current_timestamp'
                    ]
                ]
            ],
            'down' => [
                'drop' => [
                    'table' => 'users'
                ]
            ]
        ];
    }

    public static function alterTable($action)
    {
        switch ($action) {
            case 'rename' :
                return [
                    'up' => [
                        'alter-table' => [
                            'rename' => [
                                'from' => '',
                                'to' => ''
                            ]
                        ]
                    ],
                    'down' => [
                        'alter-table' => [
                            'rename' => [
                                'from' => '',
                                'to' => ''
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
        $actions = explode('_', $command);
        switch ($actions[0]) {
            case 'alter-table' :
                $migration = self::alterTable($actions[1]);
            break;
            default :
                $migration = self::createTable();
            break;
        } 

        $migration_file_content = json_encode($migration, \JSON_PRETTY_PRINT);

        return file_put_contents($migration_file_path, $migration_file_content);
    }
}