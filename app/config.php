<?php
$db_host = 'mysql';
$db_user = 'root';
$db_pwd = 'example';
$db_base = 'db_task';

spl_autoload_register(function ($name) {
    require('class/' . $name . '.php');
});

$db = new MySqlDB($db_host, $db_base,  $db_user,$db_pwd);

$model = new Model($db);

$view = new View($model);

$controller = new Controller($model);