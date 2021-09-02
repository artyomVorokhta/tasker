<?php

session_start();
include_once "../common/connect.php";
header("Content-type: application/json");
//$user_id = $_SESSION['userId'];
$user_id = 1;

$qry = "SELECT users.id, users.name, GROUP_CONCAT(accounting.room_id SEPARATOR ',') as roomIds
        FROM users
        LEFT JOIN accounting
        ON users.id = accounting.user_id
        WHERE users.id != 1
        AND users.id != '" .$user_id. "'
        GROUP BY users.id";

echo(json_encode($qry));

// $users_query = mysqli_query($connect, $qry);

// $users = [];
// while ($user = mysqli_fetch_object($users_query)) {
//     array_push($users, $user);
// }

// http_response_code(200);
// echo(json_encode($users));
