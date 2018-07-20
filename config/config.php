<?php

return [
    'db' => [
        'host' => 'localhost',
        'port' => 3306,
        'username' => 'postoffice',
        'password' => 'Fx5Yaj84',
        'dbname' => 'skeleton',
    ],
    'options' => [
        'transit_domain'      => 'http://rambler.ru/',
        'default_tracker_url' => 'http://google.com/',
    ],
    'routes' => [
        '/' => 'index/index',
    ],
];
