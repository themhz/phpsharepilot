<?php
require_once 'config.php';
require_once 'classes/YoutubeService.php';

$conn = new mysqli($servername, $username, $password, $dbname);

function getVideosFromDataBase($conn) {
    $sql = "SELECT u.*, sp.id as 'scheduled_id', sp.post_time, sp.is_posted FROM urls u LEFT JOIN scheduled_posts sp ON u.id = sp.url_id";
    $result = $conn->query($sql);

    $videos = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $videos[] = $row;
        }
    }
    return $videos;
}


function isVideoInDatabase($conn, $video_url) {

    $sql = "SELECT * FROM urls WHERE url = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $video_url);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }

    $stmt->close();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Add this before the closing </head> tag in your index.php file -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Replace the <input type="button" value="Add"> line in your index.php file with the following -->

    <title>Tree Menu Template</title>
    <link rel="stylesheet" href="main.css">

</head>
<body>
<div class="top-bar">
    <input type="search" placeholder="Search...">
</div>
<div class="container">
    <div class="menu">
        <ul>
            <li class="parent">
                <a href="?page=database">DataBase</a>
            </li>
            <li class="parent">
                <a href="?page=Youtube">Youtube</a>
            </li>
            <li class="parent">
                Parent 2
                <ul>
                    <li class="child">Child 2-1</li>
                    <li class="child">Child 2-2</li>
                </ul>
            </li>
        </ul>
    </div>
    <div class="main" id="main-content">
        <?php
            if(!isset($_REQUEST["page"]) || (trim(strtolower($_REQUEST["page"]))=="youtube" || trim(strtolower($_REQUEST["page"]))== "")){
                include_once "actions/youtubelist.php";
            }else{
                include_once "actions/databaselist.php";
            }

        ?>
    </div>
</div>

</body>
</html>


