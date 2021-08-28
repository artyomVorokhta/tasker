<?php

session_start();
include_once "../common/connect.php";
header("Content-type: application/json");

$body = json_decode(file_get_contents("php://input"));
$room = $body->room;
$creator_id = $_SESSION["userId"];

$errorResponse = new stdClass();

if (!trim($room->title)) {
    http_response_code(400);
    $errorResponse->message = "Введите название комнаты";
    die(json_encode($errorResponse));
}

mysqli_query(
    $connect,
    "INSERT INTO `rooms` (`title`, `creator_id`)
    VALUES ('" .
        $room->title .
        "','" .
        $creator_id .
        "')"
);

$room_id = mysqli_insert_id($connect);

mysqli_query(
    $connect,
    "INSERT INTO `accounting` (`user_id`, `room_id`) 
    VALUES ('" .
        $creator_id .
        "','" .
        $room_id .
        "')"
);

http_response_code(200);
