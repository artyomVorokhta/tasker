<?php

session_start();
include_once "../common/connect.php";

$body = json_decode(file_get_contents("php://input"));
$room = $body->room;
$title = $room->title;
$room_id = $room->id;

$errorResponse = new stdClass();

if (!trim($title)) {
    http_response_code(400);
    $errorResponse->message = "Введите название комнаты";
    die(json_encode($errorResponse));
}

$check_rooms = mysqli_query(
    $connect,
    "SELECT * FROM `rooms` WHERE `title` = '" . $title . "'"
);

header("Content-type: application/json");

if (mysqli_num_rows($check_rooms) > 0) {
    http_response_code(400);
    $errorResponse->message = "Название занято";
    die(json_encode($errorResponse));
}

$check_creator = mysqli_query(
    $connect,
    "SELECT `creator_id` FROM `rooms`  WHERE `id` =  '" . $room_id . "'"
);

$creator_id = mysqli_fetch_object($check_creator)->creator_id;

if ($_SESSION["userId"] != $creator_id && $_SESSION["userId"] != "1") {
    http_response_code(403);
    $errorResponse->message = "Недостаточно прав";
    die(json_encode($errorResponse));
}

mysqli_query(
    $connect,
    "UPDATE `rooms` SET `title`= '" .
        $title .
        "' 
    WHERE `id` =  '" .
        $room_id .
        "'"
);

http_response_code(200);
