<?php
header("Content-Type: application/json");

// Replace these with your database connection details
require_once '../config.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT a.title as task, DATE(b.post_time) AS date, TIME(b.post_time) AS time, b.is_posted as posted FROM urls a INNER JOIN scheduled_posts b ON a.id = b.url_id";
$result = $conn->query($sql);

$tasks = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        array_push($tasks, $row);
    }
}

echo json_encode($tasks);

$conn->close();
