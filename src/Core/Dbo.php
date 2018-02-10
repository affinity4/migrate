<?php
namespace Affinity4\Migrate\Core;

/**
 * --------------------------------------------------
 * Dbo Class
 * --------------------------------------------------
 * 
 * @author Luke Watts <luke@affinity4.ie>
 * 
 * @since 0.0.1
 * 
 * @package \Affinity4\Migrate\Core
 */
class Dbo extends \PDO
{
    protected static $instance;

    private $config = [
        'driver'    => '',
        'host'      => '',
        'name'      => '',
        'user'      => '',
        'pass'      => ''
    ];

    /**
     * --------------------------------------------------
     * Constructor
     * --------------------------------------------------
     * 
     * @author Luke Watts <luke@affinity4.ie>
     * 
     * @since 0.0.1
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $opt = array(
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
            \PDO::ATTR_EMULATE_PREPARES   => FALSE,
        );
        
        $defaults = [
            'driver' => 'mysql',
            'host'   => '127.0.0.1',
            'name'   => 'test',
            'user'   => 'root',
            'pass'   => '',
            'charset' => 'utf8'
        ];

        foreach ($defaults as $key => $val) {
            $this->config[$key] = (array_key_exists($key, $config) && !empty($config[$key])) 
                ? $config[$key]
                : $val;
        }

        $dsn = sprintf(
            '%s:host=%s;dbname=%s;charset=%s', 
            $this->config['driver'],
            $this->config['host'],
            $this->config['name'],
            $this->config['charset']
        );

        parent::__construct($dsn, $this->config['user'], $this->config['pass'], $opt);
    }

    /**
     * --------------------------------------------------
     * Get Config
     * --------------------------------------------------
     * 
     * @author Luke Watts <luke@affinity4.ie>
     * 
     * @since 0.0.1
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * --------------------------------------------------
     * Connect
     * --------------------------------------------------
     * 
     * @author Luke Watts <luke@affinity4.ie>
     * 
     * @since 0.0.1
     *
     * @param array $config
     * 
     * @return \Affinity4\Migrate\Core\Dbo
     */
    public static function connect(array $config = [])
    {
        if (self::$instance === null){
            self::$instance = new self($config);
        }

        return self::$instance;
    }

    /**
     * --------------------------------------------------
     * Get Instance
     * --------------------------------------------------
     * 
     * @author Luke Watts <luke@affinity4.ie>
     * 
     * @since 0.0.1
     *
     * @return \Affinity4\Migrate\Core\Dbo
     */
    public static function getInstance()
    {
        if (self::$instance === null){
            self::$instance = self::connect($config);
        }

        return self::$instance;
    }

    /**
     * --------------------------------------------------
     * Query
     * --------------------------------------------------
     * 
     * Build and execute prepared statements in one
     * command
     * 
     * @author Luke Watts <luke@affinity4.ie>
     * 
     * @since 0.0.1
     *
     * @param string $sql
     * @param array $args
     * 
     * @return bool
     */
    public function query($sql, $args = [])
    {
        $query = $this->prepare($sql);
        $query->execute($args);

        return $query;
    }
}