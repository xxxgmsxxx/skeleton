<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../core/bootstrap.php';

use \core\App;

$instance = new App();
$instance->run();
