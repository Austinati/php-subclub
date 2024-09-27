<?php

namespace Subclub;

use Exception;
use GuzzleHttp\Client as GuzzleClient;

class Client {
    private $apiUrl = 'https://api.sub.club/public';
    private $apiKey;
    public $httpClient;

    public function __construct($apiKey) {
        $this->apiKey = $apiKey;
        $this->httpClient = new GuzzleClient();
    }

    // creates a new post on sub.club with the given parameters.
    public function createPost($params) {
        $response = $this->httpClient->request('POST', "{$this->apiUrl}/post", [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$this->apiKey}",
            ],
            'json' => $params,
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new Exception("Failed to create post: " . $response->getReasonPhrase());
        }

        return json_decode($response->getBody(), true);
    }

    // edits the given post with the supplied PostUpdateParams.
    public function editPost($params) {
        $response = $this->httpClient->request('PUT', "{$this->apiUrl}/post/edit", [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$this->apiKey}",
            ],
            'json' => $params,
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new Exception("Failed to edit post: " . $response->getReasonPhrase());
        }

        return json_decode($response->getBody(), true);
    }

    // deletes a post with the given postID.
    public function deletePost($params) {
        $response = $this->httpClient->request('DELETE', "{$this->apiUrl}/post/delete", [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$this->apiKey}",
            ],
            'json' => $params,
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new Exception("Failed to delete post: " . $response->getReasonPhrase());
        }
    }
}
