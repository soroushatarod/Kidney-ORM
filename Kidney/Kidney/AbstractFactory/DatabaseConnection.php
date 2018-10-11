<?php

namespace Kidney\AbstractFactory;

use Kidney\AbstractFactory\Singleton;

/**
 * Abstract Dataabase class
 *
 * @author dazdingo
 */
abstract class DatabaseConnection
{

    /**
     * PDO connection
     *
     * @var \PDO
     */
    protected static $pdo;

    /**
     * database host to connect
     * @var string
     */
    protected $host;

    /**
     * Database name
     * @var string
     */
    protected $dbName;

    /**
     * Username of the database
     * @var string
     */
    protected $username;


    /**
     * Database password
     *
     * @var string
     */
    protected $password;

    protected static $type;


    /**
     * ERRPR Code Invalid Query
     */
    const ERROR_NUMBER_INVALID_QUERY = 1064;

    public function __construct($connection)
    {
        $this->setConnectionDetails($connection);
        $this->connect();
    }

    /**
     * Creates a datasebase connection
     */
    public function connect()
    {
        $dsn = sprintf('mysql:host=%s;dbname=%s', $this->getHost(), $this->getDbName());
        try {
            self::$pdo = new \PDO($dsn, $this->getUsername(), $this->getPassword());
        } catch (\Exception $ex) {
        }

    }

    /**
     * Performs a fetch all
     * @param string $query
     * @return array
     * @throws \Exception
     */
    public static function selectFetchAll($query)
    {

        if ($result = static::$pdo->query($query)) {
            if ($result->num_rows != 0) {
                while ($row = $result->fetch_object()) {
                    $output[] = $row;
                }
                return $output;
            } else {
                throw new \Exception('NO SEARCH RESULTS');
            }
        }

        if (mysqli_errno(static::$_mysqli)) {
            $message = "FULL QUERY: " . $query;

            throw new \mysqli_sql_exception(mysqli_error(static::$_mysqli) . ' ' . $message, mysqli_errno(static::$_mysqli));
        }
    }

    /**
     * Performs an insert query into the database
     *
     * @param string $sql
     * @return int|string
     * @throws \Exception
     */
    public function insert($sql)
    {
        if (!static::$_mysqli->query($sql))
            throw new \Exception('INSERT FAIL ' . static::$_mysqli->error . " FULL QUERY: " . $sql);
        else
            return mysqli_insert_id(static::$_mysqli);
    }

    /**
     *
     * @param $sql
     * @return int
     * @throws \Exception
     */
    public function executeNonQuery($sql)
    {

        if (static::$_mysqli->query($sql)) {
            $returnCode = mysqli_affected_rows(static::$_mysqli);
            if ($returnCode == 0 || $returnCode == -1) {
                throw new \Exception('DELETE FAIL | RETURN CODE: ' . $returnCode);
            } else {
                return $returnCode;
            }
        }
    }

    public function sanitizeQuery($sql)
    {
        return mysqli_real_escape_string($this->_mysqli, $sql);
    }

    public function getLastInsertId()
    {
        return mysqli_insert_id($this->_mysqli);
    }

    /**
     * Configures the database credentials
     *
     * @param array $config
     */
    public function setConnectionDetails(array $config)
    {
        $this->setDatabaseName($config);
        $this->setHostname($config);
        $this->setPassword($config);
        $this->setUsername($config);
    }

    public function setDatabaseName($config)
    {
        $this->dbName = $config['database'] ?? "";
    }

    public function setHostname($config)
    {
        $this->host = $config['hostname'] ?? "";
    }

    public function setUsername($config)
    {
        $this->username = $config['username'] ?? "";
    }

    public function setPassword($config)
    {
        $this->password = $config['password'] ?? "";
    }

    /**
     * Get hostname
     *
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Get the database name
     *
     * @return string
     */
    public function getDbName()
    {
        return $this->dbName;
    }

    /**
     * Get the username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Get the password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }


}