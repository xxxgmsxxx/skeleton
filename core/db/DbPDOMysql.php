<?php
namespace core;


class Db
{
    /** @var $_connection \PDO */
    private $_connection;
    /** @var $_statement \PDOStatement */
    private $_statement;

    public function __construct($config)
    {
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset=utf8";
        $opt = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $this->_connection = new \PDO($dsn, $config['username'], $config['password'],  $opt);
    }

    public function exec($query, $params = [])
    {
        if (!is_array($params) || count($params) == 0)
        {
            $this->_statement = $this->_connection->query($query);
        }
        else
        {
            $this->_statement = $this->_connection->prepare($query);
            $this->_statement->execute($params);
        }
    }

    public function fetch()
    {
        if ($this->_statement == null)
        {
            return null;
        }
        return $this->_statement->fetch();
    }

    public function fetchAll()
    {
        if ($this->_statement == null)
        {
            return null;
        }
        return $this->_statement->fetchAll();
    }

    public function fetchCol()
    {
        if (!$this->_statement)
        {
            return null;
        }
        return $this->_statement->fetchColumn();
    }

    public function fetchPairs()
    {
        if ($this->_statement == null)
        {
            return null;
        }
        return $this->_statement->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    public function rows()
    {
        if (!$this->_statement)
        {
            return 0;
        }
        $rs = $this->_statement->rowCount();
        return ($rs === null) ? 0 : $rs;
    }

}