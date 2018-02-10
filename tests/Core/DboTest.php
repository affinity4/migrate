<?php
namespace Affinity4\Migrate\Test\Core;

use PHPUnit\Framework\TestCase;
use Affinity4\Migrate;

class DboTest extends TestCase
{
    use Migrate\Test\InvokerTrait;

    public function testConfigIsSet()
    {
        
        $dbo = Migrate\Core\Dbo::connect(['name' => 'affinity4_migrate_tests']);

        $expected = [
            'driver' => 'mysql',
            'host' => '127.0.0.1',
            'name' => 'affinity4_migrate_tests',
            'user' => 'root',
            'pass' => '',
            'charset' => 'utf8'
        ];

        $this->assertEquals($expected, Migrate\Core\Dbo::getInstance()->getConfig());
    }

    public function testIsSingleton()
    {
        $dbo = Migrate\Core\Dbo::connect(['name' => 'affinity4_migrate_tests'])->getConfig();

        $dbo2 = Migrate\Core\Dbo::connect(['name' => 'affinity4_2nd_migrate_tests'])->getConfig();

        $dbo3 = Migrate\Core\Dbo::getInstance(['name' => 'affinity4_3rd_migrate_tests'])->getConfig();

        $dbo4 = Migrate\Core\Dbo::getInstance(['name' => 'affinity4_4th_migrate_tests'])->getConfig();

        $this->assertEquals($dbo, $dbo2);
        $this->assertEquals($dbo, $dbo3);
        $this->assertEquals($dbo, $dbo4);
    }

    // public function testQuery()
    // {
    //     $dbo = Migrate\Core\Dbo::connect(['name' => 'affinity4_migrate_tests']);
    //     $
    // }
}