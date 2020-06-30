<?php
session_start();

include_once('config.php');

$action = $_GET['action'];

$id = (int)htmlspecialchars($_POST['id'], ENT_QUOTES);
$user = htmlspecialchars($_POST['user'], ENT_QUOTES);
$email = htmlspecialchars($_POST['email'], ENT_QUOTES);
$context = htmlspecialchars($_POST['context'], ENT_QUOTES);
$status = (int)htmlspecialchars($_POST['status'], ENT_QUOTES);

if (strtolower($action) == 'insert') {
    $data = $controller->addTask($user, $email, $context);
}

if ($_SESSION['admin'] == 1) {
    if (strtolower($action) == 'update') {
        $data = $controller->updateTask($id, $user, $email, $context, $status);
    }

    if (strtolower($action) == 'delete') {
        $data = $controller->deleteTask($id);
    }
} else {
    $data = array("status" => -1,"retmsg" => 'не обходимо войти в систему!');
}

header("Content-type:application/json");
echo json_encode($data);