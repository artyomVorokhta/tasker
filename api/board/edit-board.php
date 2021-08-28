<?php

session_start();
include_once "../common/connect.php";

$body = json_decode(file_get_contents("php://input"));
$board = $body->board;
$title = $board->title;
$board_id = $board->id;

$errorResponse = new stdClass();

if (!trim($title)) {
    http_response_code(400);
    $errorResponse->message = "Введите название доски";
    die(json_encode($errorResponse));
}

mysqli_query(
    $connect,
    "UPDATE `boards` SET `title`= '" .
        $title .
        "' 
    WHERE `id` =  '" .
        $board_id .
        "'"
);

http_response_code(200);
