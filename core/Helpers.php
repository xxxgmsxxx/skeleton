<?php
namespace core;

class Helpers
{
    private $variableCache = [];

    /**
     * Get real user IP from list of server variables
     * @return string
     */
    public function getRealIp()
    {
        if (isset($this->variableCache['GET_REAL_IP_RESULT']))
        {
            return $this->variableCache['GET_REAL_IP_RESULT'];
        }
        $ip = '127.0.0.1';
        $params = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];

        foreach($params as $param)
        {
            if (isset($_SERVER[$param]) && !empty($_SERVER[$param]))
            {
                if (strstr($_SERVER[$param], ','))
                {
                    $tmp = explode(',', $_SERVER[$param]);
                    $_SERVER[$param] = $tmp[count($tmp) - 1];
                }
                if (filter_var($_SERVER[$param], FILTER_VALIDATE_IP))
                {
                    $ip = $_SERVER[$param];
                }
            }
        }

        $this->variableCache['GET_REAL_IP_RESULT'] = $ip;

        return $ip;
    }
}