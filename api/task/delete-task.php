<?php

session_start();
include_once "../common/connect.php";

$body = json_decode(file_get_contents("php://input"));
$task_id = $body->taskId;

$errorResponse = new stdClass();

mysqli_query($connect, "DELETE FROM `tasks` WHERE `id` =  '" . $task_id . "'");

http_response_code(200);
