<?php
header("Content-type:application/json");

spl_autoload_register(function ($name) {
    require('../class/' . $name . '.php');
});

$listTask = array();

array_push($listTask, new Task('user1', 'user1@email.net', 'task #1', 0));
array_push($listTask, new Task('user2', 'user2@email.net', 'task #2', 0));
array_push($listTask, new Task('user3', 'user3@email.net', 'task #3', 0));
array_push($listTask, new Task('user4', 'user4@email.net', 'task #4', 0));

echo json_encode($listTask);