<?php

function addScheduledPost($url_id, $post_time, $is_posted = 0) {
    require_once '../config.php';
    // Create a new MySQLi connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check if the connection was successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the scheduled post already exists
    $check_sql = "SELECT * FROM scheduled_posts WHERE url_id = ? AND post_time = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("is", $url_id, $post_time);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo "This post is already scheduled.";
        $check_stmt->close();
    } else {
        $check_stmt->close();

        // Prepare the SQL query
        $sql = "INSERT INTO scheduled_posts (url_id, post_time, is_posted) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bind_param("isi", $url_id, $post_time, $is_posted);

        // Execute the query and check the result
        if ($stmt->execute()) {
            echo "New scheduled post added successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        // Close the statement
        $stmt->close();
    }

    // Close the connection
    $conn->close();
}


// Example usage
$url_id = $_POST['url_id'];
$post_time = $_POST['post_time'];
addScheduledPost($url_id, $post_time);
