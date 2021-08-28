<?php

session_start();
include_once "../common/connect.php";

$body = json_decode(file_get_contents("php://input"));
$task = $body->task;
$title = $task->title;
$description = $task->description;
$stage = 0;
$board_id = $body->boardId;

$executor_id = $_SESSION["userId"];

$errorResponse = new stdClass();

if (!trim($title) || !trim($description)) {
    http_response_code(400);
    $errorResponse->message = "Заполните пустые поля";
    die(json_encode($errorResponse));
}

mysqli_query(
    $connect,
    "INSERT INTO `tasks` (`title`, `description`, `stage`, `executor_id`, `board_id`) 
    VALUES ('" .
        $title .
        "','" .
        $description .
        "','" .
        $stage .
        "','" .
        $executor_id .
        "','" .
        $board_id .
        "')"
);

http_response_code(200);
$_SESSION["message"] = "Создание прошло успешно";
echo json_encode($_SESSION["message"]);
