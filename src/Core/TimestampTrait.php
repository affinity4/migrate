<?php
namespace Affinity4\Migrate\Core;

trait TimestampTrait
{
    public function now($timezone = 'Europe/Dublin')
    {
        date_default_timezone_set($timezone);

        return date('Y-m-d H:i:s');
    }

    public function migrationTimestamp($timezone = 'Europe/Dublin')
    {
        date_default_timezone_set($timezone);

        return date('YmdHis');
    }
}