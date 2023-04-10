<?php
$videos = getVideosFromDataBase($conn);
?>
<h1>Videos in database</h1>
<table>
    <tr>
        <th>Image</th>
        <th>Title / Url: </th>
        <th>dateInserted:</th>
        <th>scheduled:</th>
        <th>Action</th>
    </tr>
    <?php
    foreach ($videos as $video) {

        $postDateValue = "";
        $postTimeValue = "";
        if (!is_null($video['post_time'])) {
            $postDateTime = new DateTime($video['post_time']);
            $postDateValue = $postDateTime->format('Y-m-d');
            $postTimeValue = $postDateTime->format('H:i');
        }

        $rowClass = $video['is_posted'] == 1 ? 'posted' : '';

        ?>
        <tr class="<?php echo $rowClass; ?>">
            <td><?php echo "<img style='width:150px;' src='{$video['thumbnailUrl']}' alt='{$video['title']}' />"; ?> </td>
            <td><?php echo $video['title'] ; ?> / <br/>
                <a href="<?php echo $video['url'] ; ?>" target="_blank"><?php echo $video['url'] ; ?></a>
            </td>
            <td><?php echo $video['dateInserted'] ; ?></td>
            <td>
                <input type="date" name="post_date" id="post_date_<?php echo $video['id']; ?>" value="<?php echo $postDateValue; ?>">
                <input type="time" name="post_time" id="post_time_<?php echo $video['id']; ?>" value="<?php echo $postTimeValue; ?>">
            </td>
            <td>
                <button class="add-video" data-video-id="<?php echo $video['id'] ?>" >Schedule</button>
                <button class="delete-video" data-scheduled-id="<?php echo $video['scheduled_id'] ?>" >Delete</button>
            </td>
        </tr>
        <?php
    }
    ?>
</table>

<script>

    <!-- Add this to your existing <script> tag in your index.php file -->
    document.querySelectorAll('.add-video').forEach((button) => {
        button.addEventListener('click', () => {
            const videoId = button.getAttribute('data-video-id');
            const postDateInput = document.getElementById(`post_date_${videoId}`);
            const postTimeInput = document.getElementById(`post_time_${videoId}`);

            const postDate = postDateInput.value;
            const postTime = postTimeInput.value;

            if (!postDate || !postTime) {
                alert("Please select a date and time for scheduling the video.");
                return;
            }

            const scheduledDateTime = `${postDate} ${postTime}`;

            $.ajax({
                type: "POST",
                url: "actions/schedule_post.php",
                data: {
                    url_id: videoId,
                    post_time: scheduledDateTime,
                },
                success: (response) => {
                    alert(response);
                },
                error: () => {
                    alert("An error occurred while scheduling the video.");
                },
            });
        });
    });


    document.querySelectorAll('.delete-video').forEach((button) => {
        button.addEventListener('click', () => {
            const scheduled_id = button.getAttribute('data-scheduled-id');

            $.ajax({
                type: "POST",
                url: "actions/delete_schedule_post.php",
                data: {
                    id: scheduled_id,
                },
                success: (response) => {
                    alert(response);
                    location.reload();
                },
                error: () => {
                    alert("An error occurred while scheduling the video.");
                },
            });
        });
    });



    //loadMenuState();
</script>