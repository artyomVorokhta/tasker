<?php

session_start();
include_once "../common/connect.php";
header("Content-type: application/json");

$body = json_decode(file_get_contents("php://input"));
$board_id = $body->boardId;

if (isset($_SESSION["auth"]) && $_SESSION["auth"] == true) {

    $board_query = mysqli_query(
        $connect,
        "SELECT `id`,`title` FROM `boards` WHERE `id` = '" . $board_id . "'"
    );

    $tasks_query = mysqli_query(
        $connect,
        "SELECT tasks.id, tasks.title, tasks.description, tasks.stage, users.name AS executor, tasks.board_id FROM `tasks` INNER JOIN `users` ON tasks.executor_id = users.id WHERE `board_id` = '" .
            $board_id .
            "'"
    );

    $board = mysqli_fetch_object($board_query);

    $allTasks = [];
    while ($task = mysqli_fetch_object($tasks_query)) {
        array_push($allTasks, $task);
    }

    $tasks1 = array_filter($allTasks, function($v) {
        return $v->stage == '0';
    });
    $tasks2 = array_filter($allTasks, function($v) {
        return $v->stage == '1';
    });
    $tasks3 = array_filter($allTasks, function($v) {
        return $v->stage == '2';
    });
    $tasks4 = array_filter($allTasks, function($v) {
        return $v->stage == '3';
    });

    $stage1 = new stdClass();
    $stage1->title = "Новые";
    $stage1->stage = "0";
    $stage1->tasks = array_values($tasks1);

    $stage2 = new stdClass();
    $stage2->title = "Разработка";
    $stage2->stage = "1";
    $stage2->tasks = array_values($tasks2);

    $stage3 = new stdClass();
    $stage3->title = "Тестирование";
    $stage3->stage = "2";
    $stage3->tasks = array_values($tasks3);

    $stage4 = new stdClass();
    $stage4->title = "Готово";
    $stage4->stage = "3";
    $stage4->tasks = array_values($tasks4);

    $stages = [$stage1, $stage2, $stage3, $stage4];

    http_response_code(200);
    $board->stages = $stages;
    echo(json_encode($board));
} else {
    http_response_code(401);
}
