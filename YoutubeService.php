<?php
require_once 'vendor/autoload.php';

use Google\Client as Google_Client;
use Google\Service\YouTube;

class YoutubeService
{
    private $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getVideosBySearchQuery($searchQuery, $maxResults=5, $minLikes = 0)
    {
        $client = new Google_Client();
        $client->setDeveloperKey($this->apiKey);
        $client->setApplicationName("SharePilot");

        $service = new Google_Service_YouTube($client);

        // Set the publishedAfter date to 1 month ago and publishedBefore to today
        $oneMonthAgo = date('Y-m-d\TH:i:s\Z', strtotime('-12 month'));
        $today = date('Y-m-d\TH:i:s\Z');

        $searchResponse = $service->search->listSearch('snippet', array(
            'q' => $searchQuery,
            'maxResults' => $maxResults,
            'type' => 'video',
            'order' => 'date', // Sort by date
//            'publishedAfter' => $oneMonthAgo,
//            'publishedBefore' => $today,
            'regionCode' => 'US',
            'videoCategoryId' => '10' // Music category ID
        ));

        $videos = array();

        foreach ($searchResponse['items'] as $searchResult) {
            if ($searchResult['id']['kind'] == 'youtube#video') {
                $videoId = $searchResult['id']['videoId'];

                // Get video statistics
                $statsResponse = $service->videos->listVideos('statistics', array(
                    'id' => $videoId
                ));

                $likesCount = $statsResponse['items'][0]['statistics']['likeCount'];

                // Check if the video has more than the specified minimum number of likes
                if ($likesCount >= $minLikes) {
                    $video = array(
                        'id' => $videoId,
                        'title' => $searchResult['snippet']['title'],
                        'description' => $searchResult['snippet']['description'],
                        'publishedAt' => $searchResult['snippet']['publishedAt'],
                        'thumbnailUrl' => $searchResult['snippet']['thumbnails']['high']['url'],
                        'channelId' => $searchResult['snippet']['channelId'],
                        'channelTitle' => $searchResult['snippet']['channelTitle'],
                        'videoUrl'=> "https://www.youtube.com/watch?v={$videoId}",
                        'likes' => $likesCount
                    );
                    $videos[] = $video;
                }
            }
        }

        return $videos;
    }


}
