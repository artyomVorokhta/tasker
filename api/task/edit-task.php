<?php

session_start();
include_once "../common/connect.php";

$body = json_decode(file_get_contents("php://input"));
$task = $body->task;
$title = $task->title;
$description = $task->description;
$stage = $task->stage;
$task_id = $task->id;

$errorResponse = new stdClass();

if (!trim($title) || !trim($description)) {
    http_response_code(400);
    $errorResponse->message = "Заполните пустые поля";
    die(json_encode($errorResponse));
}

mysqli_query(
    $connect,
    "UPDATE `tasks` SET `title`= '" .$title. "', `description`= '" .$description. "', `stage`= '" .$stage. "' 
    WHERE `id` =  '" .$task_id. "'"
);

http_response_code(200);

