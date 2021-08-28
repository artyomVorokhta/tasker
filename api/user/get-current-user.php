<?php

session_start();
include_once "../common/connect.php";
header("Content-type: application/json");

if (isset($_SESSION["auth"]) && $_SESSION["auth"] == true) {
    $userId = $_SESSION["userId"];

    $user_query = mysqli_query(
        $connect,
        "SELECT `id`, `name` FROM `users` WHERE `id` = '" . $userId . "'"
    );

    $user = mysqli_fetch_object($user_query);

    http_response_code(200);
    $successResponse = new stdClass();
    $successResponse->user = $user;
    echo json_encode($successResponse);
} else {
    http_response_code(401);
}
