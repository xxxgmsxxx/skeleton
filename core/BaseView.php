<?php
namespace core;

class BaseView
{
    public $basePath = '';
    public $layoutPath = '';

    public function __construct($basePath)
    {
        $this->basePath = $basePath;
        $this->layoutPath = $this->basePath . '_layout' . DIRECTORY_SEPARATOR;
    }

    public function render($fileName = '', $params = [])
    {
        foreach($params as $name => $value)
        {
            //$this->{$name} = $value;
        }
        echo 'VIEW BEGIN<br>';
        var_dump($this);
        echo '<br>VIEW end<br>';
    }

    private function renderHead()
    {

    }

    private function renderBody()
    {

    }

    private function renderFooter()
    {

    }

}