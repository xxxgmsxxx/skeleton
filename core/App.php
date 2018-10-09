<?php
namespace core;

use core\routers\Router;
use core\user\UserStandart;
use core\db\DbPDOMysql;


class App extends BaseClass
{
    public function __construct()
    {
        parent::__construct();

        $this->db = new DbPDOMysql($this->config['db']);
    }

    public function run()
    {

        $uri = $_SERVER['REQUEST_URI'];
        if(strpos($uri, '?') !== false)
        {
            $uri = substr($uri, 0, strpos($uri, '?'));
        }
        $result = [];
        if(isset($this->config['routes'][$uri]))
        {
            list($result['controller'], $result['action']) = explode('/', $this->config['routes'][$uri]);
            $result['controller'] = ucfirst($result['controller']) . 'Controller';
            $result['action'] = 'action'.ucfirst($result['action']);
        }
        if(!isset($result['controller']) || !isset($result['action']))
        {
            $router = false;
            if(isset($this->config['options']['router']) && is_file(__DIR__.'/routers/'.$this->config['options']['router'].'.php'))
            {
                $routerName = trim($this->config['options']['router']);
                require __DIR__.'/routers/'.$routerName.'.php';
                $router = new $routerName($this->config);
            }
            if(!$router)
            {
                $router = new Router($this->config);
            }
            $result = $router->getRoute($uri);
        }
        if(isset($result['controller']) && isset($result['action']))
        {
            $fileName = $this->rootPath.'app/Controllers/'.$result['controller'].'.php';
            if(is_file($fileName))
            {
                require $fileName;
                try
                {
                    $this->session = new Session();
                    $this->user = new UserStandart($this);
                    $action = $result['action'];
                    $controller = new $result['controller']($this);
                    $controller->$action();
                }
                catch(\Exception $e)
                {
                    $fl = fopen($this->rootPath.'runtime/logs/app.log', 'a');
                    if ($fl) {
                        fputs($fl, '['.date('Y-m-d H:i:s').'] '.$e->getMessage()."\n");
                        fputs($fl, $_SERVER['REQUEST_URI']."\n");
                        ob_start();
                        var_dump($_REQUEST);
                        $txt = ob_get_contents();
                        ob_end_clean();
                        fputs($fl, $txt."\n---------------------------------\n");
                        fclose($fl);
                    }
                    $this->httpError(404, 'Not Found');
                }
                return true;
            }
        }
        $this->httpError(404, 'Not Found');
    }
}