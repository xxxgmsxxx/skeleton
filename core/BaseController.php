<?php
namespace core;

class BaseController
{
    public $app;
    public $view;

    public function __construct($app)
    {
        $this->app = $app;
        $this->view = new BaseView($this->app->rootPath . 'app/Views');
    }
}