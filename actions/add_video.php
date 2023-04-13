<?php
require_once '../config.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$video_id = $_POST['video_id'];
$title = $_POST['title'];
$video_url = $_POST['video_url'];
$thumbnail_url = $_POST['thumbnail_url'];
$published_at = $_POST['published_at'];

// Check if the video is already in the database
$check_sql = "SELECT * FROM urls WHERE url = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("s", $video_url);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows === 0) {
    // Insert the video into the database
    $sql = "INSERT INTO urls (url, title, dateInserted, source, type, thumbnailUrl) VALUES (?, ?, ?, ?, ?, ?)";

    $dateInserted = date('Y-m-d H:i');
    $source = 1;
    $type = 1;
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssiis", $video_url, $title, $dateInserted, $source, $type, $thumbnail_url);

    if ($stmt->execute()) {
        echo "New video added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
} else {
    echo "Video is already in the database.";
}

$check_stmt->close();
$conn->close();
