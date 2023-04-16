<?php

header("Content-Type: application/json");
require_once '../config.php';

$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Connection failed: " . $mysqli->connect_error]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);
$start_datetime = $data["start_datetime"];
$hourInterval = $data["hourInterval"];

if (!empty($start_datetime)) {
    $stmt = $mysqli->prepare("CALL schedule_posts(?, ?)");
    $stmt->bind_param("si", $start_datetime, $hourInterval);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(["message" => "Posts scheduled successfully."]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Error scheduling posts: " . $stmt->error]);
    }

    $stmt->close();
} else {
    http_response_code(400);
    echo json_encode(["error" => "start_datetime is required."]);
}

$mysqli->close();

