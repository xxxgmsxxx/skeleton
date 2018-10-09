<?php
use \core\BaseController;

class IndexController extends BaseController
{
    public function actionIndex()
    {
        $this->view->render('index/index');
    }
}