<?php
require_once 'config.php';
$youtubeService = new YoutubeService($youtubeapiKey);
$searchQuery= "";
$videoCategoryId = '10';
if(!isset($_POST["txtsearch"]) || empty($_POST["txtsearch"])){
    $searchQuery = 'boxing workout, motivation, hip hop, rap';

}else{
    $searchQuery = $_POST["txtsearch"];
    $videoCategoryId = null;
}

$maxResults = 50;
$videos = $youtubeService->getVideosBySearchQuery($searchQuery, $maxResults, 0, $videoCategoryId);
?>
<h1>Videos from youtube</h1>
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
                <a href="<?php echo $video['videoUrl'] ; ?>" target="_blank"><?php echo $video['videoUrl'] ; ?></a>/
                <?php echo $video['duration'] ; ?>
            </td>
            <td><?php echo $video['publishedAt'] ; ?></td>
            <td>
                <button class="add-video" data-video-id="<?php echo $video['id'] ?>" data-title="<?php echo htmlspecialchars($video['title'], ENT_QUOTES) ?>" data-video-url="<?php echo $video['videoUrl'] ?>" data-thumbnail-url="<?php echo $video['thumbnailUrl'] ?>" data-published-at="<?php echo $video['publishedAt'] ?>">Add</button>
                <button class="delete-video" data-video-url="<?php echo $video['videoUrl'] ?>" >Delete</button>
            </td>
        </tr>
        <?php
    }
    ?>
</table>

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
                url: "actions/add_video.php",
                data: { video_id, title, video_url, thumbnail_url, published_at },
                success: (response) => {
                    alert(response);
                    location.reload();
                },
                error: () => {
                    alert("An error occurred while adding the video.");
                },
            });
        });
    });

    document.querySelectorAll('.delete-video').forEach((button) => {
        button.addEventListener('click', () => {
            const url = button.getAttribute('data-video-url');
            $.ajax({
                type: "POST",
                url: "actions/delete_video.php",
                data: {
                    url: url,
                },
                success: (response) => {
                    alert(response);
                    location.reload();
                },
                error: () => {
                    alert("An error occurred while deleting the video.");
                },
            });
        });
    });


    //loadMenuState();
</script>