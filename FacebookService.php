<?php

class FacebookService
{
    private $accessToken;
    private $pageId;

    public function __construct($accessToken, $pageId)
    {
        $this->accessToken = $accessToken;
        $this->pageId = $pageId;
    }

    public function postToFacebookPageAsync($message, $link)
    {
        try {
            $requestUrl = "https://graph.facebook.com/v16.0/{$this->pageId}/feed?access_token={$this->accessToken}";

            $content = http_build_query([
                'message' => $message,
                'link' => $link
            ]);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $requestUrl);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, 0);

            $response = curl_exec($ch);

            if ($response === false) {
                throw new Exception("Request failed: " . curl_error($ch));
            }

            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode != 200) {
                throw new Exception("Request failed with status code: {$httpCode} and response: {$response}");
            }

            $responseObject = json_decode($response);

            echo "Video link posted successfully! Post ID: " . $responseObject->id . "\n";
        } catch (Exception $ex) {
            echo "Error: " . $ex->getMessage() . "\n";
        }
    }
}

