<?php

session_start();
include_once "../common/connect.php";
header("Content-type: application/json");

if (isset($_SESSION["auth"]) && $_SESSION["auth"] == true) {
    $userId = $_SESSION["userId"];
    $isAdmin = $_SESSION["admin"];
    $rooms = [];

    if ($isAdmin) {
        $rooms_query = mysqli_query(
            $connect,
            "SELECT `id`, `title` FROM `rooms` WHERE `deleted` != '1'"
        );

        while ($room = mysqli_fetch_object($rooms_query)) {
            array_push($rooms, $room);
        }

        http_response_code(200);
        die(json_encode($rooms));
    }

    $rooms_ids_query = mysqli_query(
        $connect,
        "SELECT `room_id` FROM `accounting` WHERE `user_id` = '" . $userId . "'"
    );

    while ($rooms_ids = mysqli_fetch_row($rooms_ids_query)) {
        $room_query = mysqli_query(
            $connect,
            "SELECT * FROM `rooms` WHERE `id` = '" .
                $rooms_ids[0] .
                "' AND `deleted` = '0'"
        );

        $room = mysqli_fetch_object($room_query);
        if ($room != null) {
            array_push($rooms, $room);
        }
    }

    http_response_code(200);
    echo json_encode($rooms);
} else {
    http_response_code(401);
}
