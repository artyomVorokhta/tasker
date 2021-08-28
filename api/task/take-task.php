<?php

session_start();
include_once "../common/connect.php";

$body = json_decode(file_get_contents("php://input"));
$task_id = $body->taskId;
$executor_id = $_SESSION["userId"];

mysqli_query(
    $connect,
    "UPDATE `tasks` SET `executor_id`= '" .
        $executor_id .
        "' 
WHERE `id` =  '" .
        $task_id .
        "'"
);

http_response_code(200);
