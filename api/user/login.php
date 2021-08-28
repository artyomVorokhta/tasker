<?php

session_start();
include_once "../common/connect.php";

$body = json_decode(file_get_contents("php://input"));
$email = $body->email;
$password = md5($body->password);

$errorResponse = new stdClass();

$check_user = mysqli_query(
    $connect,
    "SELECT * FROM `users` WHERE `email` = '" .
        $email .
        "' AND `password` = '" .
        $password .
        "'"
);

header("Content-type: application/json");

if (mysqli_num_rows($check_user) > 0) {
    $_SESSION["auth"] = true;
    $user = mysqli_fetch_object($check_user);
    $_SESSION["userId"] = $user->id;
    if ($user->id == "1") {
        $_SESSION["admin"] = true;
    } else {
        $_SESSION["admin"] = false;
    }
    http_response_code(200);
    echo json_encode($user);
} else {
    $_SESSION["auth"] = false;
    $errorResponse->message = "Пользователь не найден";
    http_response_code(404);
    echo json_encode($errorResponse);
}
