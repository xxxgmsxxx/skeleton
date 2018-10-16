<?php
use \core\BaseController;

class IndexController extends BaseController
{
    public function actionIndex()
    {
        $this->view->render('index/index', ['param1' => 'value1', 'param2' => 'value2']);
    }
}