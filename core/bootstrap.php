<?php
set_include_path(get_include_path() . ';' . realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR));

spl_autoload_register(function ($class_name) {
    require $class_name . '.php';
});
