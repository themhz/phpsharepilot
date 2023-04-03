<?php
require_once 'YoutubeService.php';
$servername = "localhost";
$username = "root";
$password = "526996";
$dbname = "sharepilot";

$conn = new mysqli($servername, $username, $password, $dbname);

$apiKey = '';
$youtubeService = new YoutubeService($apiKey);

$searchQuery = 'boxing workout, motivation, hip hop, rap';
$maxResults = 50;
$videos = $youtubeService->getVideosBySearchQuery($searchQuery, $maxResults, 0);


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
    <style>
        * {
            box-sizing: border-box;
        }
        html, body {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .top-bar {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f0f0f0;
            padding: 10px;
        }
        .top-bar input[type="search"] {
            width: 50%;
            padding: 5px;
        }
        .container {
            display: flex;
            height: calc(100% - 50px);
        }
        .menu {
            width: 20%;
            height: 100%;
            background-color: #f0f0f0;
            padding: 20px;
            overflow-y: auto;
        }
        .menu ul {
            list-style-type: none;
            padding: 0;
        }
        .menu ul ul {
            display: none;
        }
        .menu li {
            cursor: pointer;
            padding: 5px;
        }
        .menu li:hover {
            background-color: #ddd;
        }
        .menu .child:hover {
            background-color: #ccc;
        }
        .menu .selected {
            background-color: #ccc;
        }
        .main {
            width: 80%;
            padding: 20px;
        }

        .video-in-database {
            background-color: #a2d5a2; /* You can choose your preferred shade of green here */
        }
    </style>
</head>
<body>
<div class="top-bar">
    <input type="search" placeholder="Search...">
</div>
<div class="container">
    <div class="menu">
        <ul>
            <li class="parent">
                Parent 1
                <ul>
                    <li class="child">Child 1-1</li>
                    <li class="child">Child 1-2</li>
                </ul>
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
        <h1>Main Content Area</h1>
        <table>
            <tr>
                <th>Image</th>
                <th>Title / Url: </th>
                <th>publishedAt:</th>
                <th>Action</th>
            </tr>
            <?php
            foreach ($videos as $video) {
                $isInDatabase = isVideoInDatabase($conn, $video['videoUrl']);
                $rowClass = $isInDatabase ? 'video-in-database' : '';
                ?>
            <tr class="<?php echo $rowClass; ?>">
                <td><?php echo "<img style='width:150px;' src='{$video['thumbnailUrl']}' alt='{$video['title']}' />"; ?> </td>
                <td><?php echo $video['title'] ; ?> / <br/>
                    <a href="<?php echo $video['videoUrl'] ; ?>" target="_blank"><?php echo $video['videoUrl'] ; ?></a>
                </td>
                <td><?php echo $video['publishedAt'] ; ?></td>
                <td>
                    <button class="add-video" data-video-id="<?php echo $video['id'] ?>" data-title="<?php echo htmlspecialchars($video['title'], ENT_QUOTES) ?>" data-video-url="<?php echo $video['videoUrl'] ?>" data-thumbnail-url="<?php echo $video['thumbnailUrl'] ?>" data-published-at="<?php echo $video['publishedAt'] ?>">Add</button>
                </td>
            </tr>
            <?php
            }
            ?>
        </table>
    </div>
</div>
<script>
    function loadMenuState() {
        const menuState = localStorage.getItem('menuState');
        if (menuState) {
            const { openParents, selectedChild } = JSON.parse(menuState);
            openParents.forEach(index => {
                document.querySelectorAll('.parent ul')[index].style.display = 'block';
            });
            if (selectedChild !== null) {
                document.querySelectorAll('.child')[selectedChild].click();
            }
        }
    }

    document.querySelectorAll('.parent').forEach((parent, parentIndex) => {
        parent.addEventListener('click', e => {
            e.stopPropagation();
            const childList = parent.querySelector('ul');
            childList.style.display = childList.style.display === 'block' ? 'none' : 'block';

            const openParents = [...document.querySelectorAll('.parent ul')]
                .map((childList, index) => childList.style.display === 'block' ? index : null)
                .filter(index => index !== null);
            const menuState = { openParents, selectedChild: null };
            localStorage.setItem('menuState', JSON.stringify(menuState));
        });
    });

    document.querySelectorAll('.child').forEach((child, childIndex) => {
        child.addEventListener('click', e => {
            e.stopPropagation();
            document.querySelectorAll('.child').forEach(otherChild => {
                otherChild.classList.remove('selected');
            });
            child.classList.add('selected');
            const mainContent = document.getElementById('main-content');
            mainContent.innerHTML = `
          <h1>${child.textContent}</h1>
          <p>Content for ${child.textContent} goes here.</p>
        `;

            const openParents = [...document.querySelectorAll('.parent ul')]
                .map((childList, index) => childList.style.display === 'block' ? index : null)
                .filter(index => index !== null);
            const menuState = { openParents, selectedChild: childIndex };
            localStorage.setItem('menuState', JSON.stringify(menuState));
        });
    });

    <!-- Add this to your existing <script> tag in your index.php file -->
    document.querySelectorAll('.add-video').forEach((button) => {
        button.addEventListener('click', () => {
            const video_id = button.dataset.videoId;
            const title = button.dataset.title;
            const video_url = button.dataset.videoUrl;
            const thumbnail_url = button.dataset.thumbnailUrl;
            const published_at = button.dataset.publishedAt;

            $.ajax({
                type: "POST",
                url: "add_video.php",
                data: { video_id, title, video_url, thumbnail_url, published_at },
                success: (response) => {
                    alert(response);
                },
                error: () => {
                    alert("An error occurred while adding the video.");
                },
            });
        });
    });


    loadMenuState();
</script>
</body>
</html>


