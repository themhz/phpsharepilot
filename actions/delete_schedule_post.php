<?php
require_once 'config.php';
function deleteScheduledPost($id) {

    // Create a new MySQLi connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check if the connection was successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "DELETE FROM scheduled_posts WHERE id = ?";
    $stmt = $conn->prepare($sql);

    // Bind the parameter
    $stmt->bind_param("i", $id);

    // Execute the query and check the result
    if ($stmt->execute()) {
        echo "Scheduled post deleted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the statement and the connection
    $stmt->close();
    $conn->close();
}

// Example usage
$id = $_POST['id'];
deleteScheduledPost($id);

