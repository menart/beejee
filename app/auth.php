<?php
$users = array('admin' => '123');

$user = strtolower($_POST['user']);
$pwd = $_POST['pwd'];

$action = $_GET['action'];

session_start();

if ($action == 'logon' && isset($user) && isset($pwd))
    $_SESSION['admin'] = $users[$user] == $pwd ? 1 : -1;
else
    $_SESSION['admin'] = 0;

header("Content-type:application/json");
echo json_encode(array('admin' => $_SESSION['admin']));