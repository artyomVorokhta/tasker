<?php

session_start();
include_once "../common/connect.php";

$body = json_decode(file_get_contents("php://input"));
$board_id = $body->boardId;

$errorResponse = new stdClass();

mysqli_query(
    $connect,
    "DELETE FROM `tasks` WHERE `board_id` =  '" . $board_id . "'"
);

mysqli_query(
    $connect,
    "DELETE FROM `boards` WHERE `id` =  '" . $board_id . "'"
);

http_response_code(200);
