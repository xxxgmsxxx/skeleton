<?php
namespace core;

class DbPDOMysql implements Db
{
    /** @var $_connection \PDO */
    protected $_connection;
    /** @var $_statement \PDOStatement */
    protected $_statement;

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

    public function lastError()
    {
        $info = $this->_statement->errorInfo();
        if (!is_array($info) || !isset($info[0]) || (int)$info[0] === 0) {
            return false;
        }

        $message = '[' . $info[0] . '] ' . (isset($info[2]) && is_string($info[2]) ? $info[2] : 'No message');

        return $message;
    }
}