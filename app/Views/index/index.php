<?php
/* @var $this core\BaseView */
$this->registerJs('https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js');

$tmpJs = <<<'JS'
alert('TEST');
JS;

$this->embedJs($tmpJs, true);
?>

<h3>Hello world!</h3>

