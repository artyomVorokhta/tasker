<?php

session_start();
include_once "../common/connect.php";

$body = json_decode(file_get_contents("php://input"));
$room_id = $body->id;

$errorResponse = new stdClass();

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
    "UPDATE `rooms` SET `deleted` = '1' WHERE `id` =  '" . $room_id . "'"
);

http_response_code(200);
