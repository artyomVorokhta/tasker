<?php

session_start();
include_once "../common/connect.php";

$body = json_decode(file_get_contents("php://input"));
$name = $body->name;
$email = $body->email;
$password = $body->password;
$passwordConfirm = $body->passwordConfirm;

$errorResponse = new stdClass();

if (
    !trim($name) ||
    !trim($email) ||
    !trim($password) ||
    !trim($passwordConfirm)
) {
    http_response_code(400);
    $errorResponse->message = "Заполните пустые поля";
    die(json_encode($errorResponse));
}

$check_user = mysqli_query(
    $connect,
    "SELECT * FROM `users` WHERE `email` = '" . $email . "'"
);

header("Content-type: application/json");

if (mysqli_num_rows($check_user) > 0) {
    http_response_code(400);
    $errorResponse->message = "Пользователь существует";
    die(json_encode($errorResponse));
}

if ($password === $passwordConfirm) {
    $password = md5($password);

    mysqli_query(
        $connect,
        "INSERT INTO `users` (`name`, `email`, `password`) 
        VALUES ('" .
            $name .
            "','" .
            $email .
            "','" .
            $password .
            "')"
    );

    http_response_code(200);
} else {
    http_response_code(400);
    $errorResponse->message = "Пароли не совпадают";
    echo json_encode($errorResponse);
}
