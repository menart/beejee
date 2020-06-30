<?php
header("Content-type:application/json");

include_once('config.php');

$listTask = array();

session_start();

$page = $_POST['page'];
if (!isset($page) || !is_numeric($page) || $page < 0 )
    $page = 0;

$order = $_POST['order'];
if (!isset($order))
    $order = 'user';

$counttask = $_POST['counttask'];
if (!isset($counttask) || !is_numeric($counttask) || $counttask < 1)
    $counttask = 3;

echo json_encode($view->getListTask($page, $counttask, $order, isset($_SESSION['admin'])?$_SESSION['admin']:0));