<?php

header("Content-Type: application/json");
require_once '../config.php';


$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Connection failed: " . $mysqli->connect_error]);
    exit();
}

$query = "DELETE FROM scheduled_posts";
$result = $mysqli->query($query);

if ($result) {
    http_response_code(200);
    echo json_encode(["message" => "All scheduled posts deleted successfully."]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Error deleting scheduled posts: " . $mysqli->error]);
}

$mysqli->close();
