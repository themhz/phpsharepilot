<?php
require_once 'FacebookService.php';
//Create token
//https://developers.facebook.com/tools/explorer/
//Debug token
//https://developers.facebook.com/tools/debug/accesstoken/

// Usage example:
$accessToken = 'EAADPBRDEIZBkBAKSwLhUOIM4QzCXjsz2PXSy7CiPCcHJDkqeM8uedJSsYsPS7JB9sE4ltiyMIzlfRUwZCI1bZCNKwoFoPjry1y6Xs3XquQd42UisQzsYvoJMep1kVfp74NSC3DVCf5OtHBikTKNNZCkm40UYZBpbV2Iswt39RlvzrqSlmlVCOHwHgX0A7Ughp5vZBmJvVVagZDZD';
$pageId = 'theotokatosfc';
$message = 'Check this out';
$link = 'https://www.youtube.com/watch?v=8B0DvmpOtMw';

$facebookService = new FacebookService($accessToken, $pageId);
$facebookService->postToFacebookPageAsync($message, $link);