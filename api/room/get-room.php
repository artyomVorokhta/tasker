<?php

session_start();
include_once "../common/connect.php";
header("Content-type: application/json");

$body = json_decode(file_get_contents("php://input"));
$room_id = $body->roomId;

if (isset($_SESSION["auth"]) && $_SESSION["auth"] == true) {
    $room_query = mysqli_query(
        $connect,
        "SELECT * FROM `rooms` WHERE `id` = '" . $room_id . "'"
    );
    $room = mysqli_fetch_object($room_query);

    $boards = [];
    $boards_query = mysqli_query(
        $connect,
        "SELECT `id`, `title` FROM `boards` WHERE `room_id` = '" .
            $room_id .
            "'"
    );
    while ($board = mysqli_fetch_object($boards_query)) {
        array_push($boards, $board);
    }

    http_response_code(200);
    $room->boards = $boards;
    echo json_encode($room);
} else {
    http_response_code(401);
}
