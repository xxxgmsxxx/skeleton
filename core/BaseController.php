<?php
namespace core;

class BaseController
{
    public $app;

    public function __construct($app)
    {
        $this->app = $app;
    }
}