<?php
require_once 'config.php';
require_once 'FacebookService.php';
//Create token
//https://developers.facebook.com/tools/explorer/
//Debug token
//https://developers.facebook.com/tools/debug/accesstoken/

// Usage example:
$accessToken = $fbkey;
$pageId = 'theotokatosfc';
$message = 'Check this out';
$link = 'https://www.youtube.com/watch?v=8B0DvmpOtMw';

$facebookService = new FacebookService($accessToken, $pageId);
$facebookService->postToFacebookPageAsync($message, $link);