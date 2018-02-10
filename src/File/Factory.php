<?php
namespace Affinity4\Migrate\File;

class Factory
{
    public static function create($type, $migration_file_path)
    {
        if ($type = 'create-table') {
            $migration = [
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

        $migration_file_content = json_encode($migration, \JSON_PRETTY_PRINT);

        return file_put_contents($migration_file_path, $migration_file_content);
    }
}