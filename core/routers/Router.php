<?php
namespace core\routers;

/**
 * Базовый класс для обработки путей
 */
//@todo: реализовать класс кэширования

class Router
{
    private $cache = null;
    private $baseCharListArr = ['-', '_', '.'];
    private $baseCharList = '-_.';

    public function __construct($config)
    {


        return $this;
    }

    public function getRoute($uri)
    {
        $result = [];
        //Убираем лишние слеши
        $uri = trim($uri,'/');

        //Ищем в кэше
        if ($this->cache !== null)
        {
            $result = $this->cache->getUriFromCache($uri);
            if (count($result) > 0)
            {
                return $result;
            }
        }

        //Разбираем URI, если одно слово - то это контроллер, если больше - то второе экшен, а дальше игнорим
        if (strpos($uri, '/') !== false)
        {
            $arr = explode('/', $uri);
            $result['controller'] = $this->completeName($arr[0]).'Controller';
            $result['action'] = 'action'.$this->completeName($arr[1]);
        }
        else
        {
            $result['controller'] = $this->completeName($uri).'Controller';
            $result['action'] = 'actionIndex';
        }

        //Если есть кэширование - запишем в кэш
        if ($this->cache !== null)
        {
            $this->cache->setUriToCache($uri, $result);
        }

        return $result;
    }

    private function completeName($name)
    {
        if (strpbrk($name, $this->baseCharList) !== false)
        {
            $result = ucwords($name, $this->baseCharList);
            $result = str_replace($this->baseCharListArr, '', $result);
        }
        else
        {
            $result = ucfirst($name);
        }

        return $result;
    }
}