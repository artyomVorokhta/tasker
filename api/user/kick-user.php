<?php

session_start();
include_once "../common/connect.php";

$body = json_decode(file_get_contents("php://input"));
$user_id = $body->userId;
$room_id = $body->roomId;


$errorResponse = new stdClass();

mysqli_query(
    $connect,
    "DELETE FROM `accounting` 
    WHERE `user_id` = '" .$user_id ."' AND `room_id` = '" .$room_id ."'"
);

http_response_code(200);
