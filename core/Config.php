<?php
namespace core;

class Config
{
    const DOMAINS_CONFIG = '../config/domains.php';

    public static function get_config()
    {
        //Берем имя конфига из domains.php в соответствии с доменом или дефолтное, если есть
        if (!is_file(self::DOMAINS_CONFIG))
        {
            throw new Exception('Wrong configuration, domains.php not found!');
        }
        $domains = include self::DOMAINS_CONFIG;
        if(!isset($domains) || !is_array($domains))
        {
            throw new Exception('Wrong configuration, domains.php corrupted!');
        }
        if (!isset($domains[$_SERVER['SERVER_NAME']]) && !isset($domains['default']))
        {
            throw new Exception('Wrong configuration, domains.php not contained current domain and default config!');
        }
        $config_file = '../config/'.(isset($domains[$_SERVER['SERVER_NAME']]) ? $domains[$_SERVER['SERVER_NAME']] : $domains['default']);
        unset($domains);

        //Подключаем сам конфиг, если файл существует
        if (!is_file($config_file))
        {
            throw new Exception('Config not found!');
        }
        $config = include $config_file;
        if(!isset($config) || !is_array($config))
        {
            throw new Exception('Configuration file corrupted!');
        }
        $tmp = $config;
        unset($config);
        return $tmp;
    }
}