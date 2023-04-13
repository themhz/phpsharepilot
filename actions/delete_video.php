<?php

function deleteScheduledPost($url) {
    // Replace these values with your database connection details

    require_once '../config.php';
    // Create a new MySQLi connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check if the connection was successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "DELETE FROM urls WHERE url = ?";
    $stmt = $conn->prepare($sql);

    // Bind the parameter
    $stmt->bind_param("s", $url);

    // Execute the query and check the result
    if ($stmt->execute()) {
        echo "Video deleted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the statement and the connection
    $stmt->close();
    $conn->close();
}

// Example usage
$url = $_POST['url'];
deleteScheduledPost($url);

