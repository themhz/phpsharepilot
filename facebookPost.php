<?php
require_once 'config.php';

// Connect to the database using PDO
try {
    $dbh = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}

// Prepare the SQL query with placeholders for the current date and time
$sql = "SELECT b.id, a.title, DATE(b.post_time) AS post_date, TIME(b.post_time) AS post_time, b.is_posted
        FROM urls a
        INNER JOIN scheduled_posts b ON a.id = b.url_id
        WHERE b.is_posted = 0
        AND b.post_time <= NOW()";

// Execute the query
$stmt = $dbh->prepare($sql);
$stmt->execute();

// Loop through the results and output them
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['id'] . ' - ' .$row['title'] . ' - ' . $row['post_date'] . ' ' . $row['post_time'] . "<br>";

    // Update the is_posted and posted_time columns
    $update_sql = "UPDATE scheduled_posts SET is_posted = 1, posted_time = NOW() WHERE id = :id";
    $update_stmt = $dbh->prepare($update_sql);
    $update_stmt->bindParam(':id', $row['id']);
    $update_stmt->execute();
}


// Close the database connection
$dbh = null;





//require_once 'FacebookService.php';
////Create token
////https://developers.facebook.com/tools/explorer/
////Debug token
////https://developers.facebook.com/tools/debug/accesstoken/
//
//// Usage example:
//$accessToken = $fbkey;
//$pageId = 'theotokatosfc';
//$message = 'Check this out';
//$link = 'https://www.youtube.com/watch?v=8B0DvmpOtMw';
//
//$facebookService = new FacebookService($accessToken, $pageId);
//$facebookService->postToFacebookPageAsync($message, $link);