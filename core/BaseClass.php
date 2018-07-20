<?php
namespace core;

use core\db\DbPDOMysql;

class BaseClass
{
    public $config;
    public $db;
    public $helpers;
    public $session;
    public $user;
    public $rootPath;

    public function __construct()
    {
        try
        {
            $this->config = Config::get_config();
        }
        catch(\Exception $e)
        {
            $this->httpError('500', 'Internal error (wrong configuration)');
            die();
        }

        $this->rootPath = realpath($_SERVER['DOCUMENT_ROOT'] . '/../') . DIRECTORY_SEPARATOR;

        $this->db = new DbPDOMysql($this->config['db']);

        $this->helpers = new Helpers();
    }

    public function httpError($code = 400, $message = 'Bad request')
    {
        header($_SERVER["SERVER_PROTOCOL"]." {$code} {$message}");
        if(is_file(__DIR__.'/errors/'.$code.'.html'))
        {
            echo file_get_contents(__DIR__.'/errors/'.$code.'.html');
        }
        else
        {
            echo "<h1>{$code} {$message}</h1>";
        }
        die();
    }
}