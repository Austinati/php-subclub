<?php

use PHPUnit\Framework\TestCase;
use Subclub\Client;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException;

class ClientTest extends TestCase {
    private $client;
    private $httpClientMock;

    protected function setUp(): void {
        $this->httpClientMock = $this->createMock(GuzzleClient::class);
        $this->client = new Client('test_api_key');
        $this->client->httpClient = $this->httpClientMock;
    }

    public function testCreatePostSuccess() {
        $params = ['content' => 'This is a test post.'];
        $response = new Response(200, [], json_encode(['success' => true]));

        $this->httpClientMock->method('request')
            ->with('POST', 'https://api.sub.club/public/post', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer test_api_key',
                ],
                'json' => $params,
            ])
            ->willReturn($response);

        $result = $this->client->createPost($params);
        $this->assertEquals(['success' => true], $result);
    }

    public function testCreatePostFailure() {
        $params = ['content' => 'This is a test post.'];
        $response = new Response(400, [], 'Bad Request');

        $this->httpClientMock->method('request')
            ->willReturn($response);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Failed to create post: Bad Request');

        $this->client->createPost($params);
    }

    public function testEditPostSuccess() {
        $params = ['postId' => 1, 'content' => 'Updated Content'];
        $response = new Response(200, [], json_encode(['success' => true]));

        $this->httpClientMock->method('request')
            ->with('PUT', 'https://api.sub.club/public/post/edit', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer test_api_key',
                ],
                'json' => $params,
            ])
            ->willReturn($response);

        $result = $this->client->editPost($params);
        $this->assertEquals(['success' => true], $result);
    }

    public function testEditPostFailure() {
        $params = ['postId' => 1, 'content' => 'Updated Content'];
        $response = new Response(400, [], 'Bad Request');

        $this->httpClientMock->method('request')
            ->willReturn($response);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Failed to edit post: Bad Request');

        $this->client->editPost($params);
    }

    public function testDeletePostSuccess() {
        $params = ['postId' => 1];
        $response = new Response(200, [], '');

        $this->httpClientMock->method('request')
            ->with('DELETE', 'https://api.sub.club/public/post/delete', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer test_api_key',
                ],
                'json' => $params,
            ])
            ->willReturn($response);

        $this->client->deletePost($params);
        $this->assertTrue(true); // If no exception is thrown, the test passes
    }

    public function testDeletePostFailure() {
        $params = ['postId' => 1];
        $response = new Response(400, [], 'Bad Request');

        $this->httpClientMock->method('request')
            ->willReturn($response);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Failed to delete post: Bad Request');

        $this->client->deletePost($params);
    }
}