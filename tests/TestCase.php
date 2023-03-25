<?php

namespace SIMDE\Ginger\Test;

require_once __DIR__ . '/../config/env.php';

use DI\ContainerBuilder;
use Exception;
use JsonException;
use PHPUnit\Framework\TestCase as PHPUnit_TestCase;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selective\TestTrait\Traits\DatabaseTestTrait;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Request as SlimRequest;
use Slim\Psr7\Uri;


class TestCase extends PHPUnit_TestCase
{
    use DatabaseTestTrait;

    /**
     * @return App
     * @throws Exception
     */

    protected $container;

    /**
     * @param string $method
     * @param string $path
     * @param string $query
     * @param array|null $body
     * @param int $expectedStatus
     *
     * @return array
     */
    protected function callGinger(
        string $method,
        string $path,
        string $query = '',
        array  $body = null,
        int    $expectedStatus = 200
    ): array
    {
        $request = $this->createRequest($method, $path, $query, $body);
        $response = $this->getAppInstance()->handle($request);
        $response->getBody()->rewind();
        try {
            $res = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
            if ($expectedStatus !== $response->getStatusCode()) {
                $this->displayContent($res);
            }
        } catch (JsonException $e) {
        }
        $this->assertSame($expectedStatus, $response->getStatusCode());
        return $res ?? [];
    }

    /**
     * @param string $method
     * @param string $path
     * @param string $query
     * @param array|null $body
     * @param array $headers
     * @param array $cookies
     * @param array $serverParams
     *
     * @return Request
     */
    private function createRequest(
        string $method,
        string $path,
        string $query = '',
        array  $body = null,
        array  $headers = ['HTTP_ACCEPT' => 'application/json'],
        array  $cookies = [],
        array  $serverParams = []
    ): Request
    {
        $uri = new Uri('', '', 80, BASE_PATH . $path, $query);
        $handle = fopen('php://temp', 'wb+');
        $stream = (new StreamFactory())->createStreamFromResource($handle);
        if ($body) {
            $stream->write(json_encode($body));
            $stream->rewind();
        }
        $h = new Headers();
        foreach ($headers as $name => $value) {
            $h->addHeader($name, $value);
        }
        return new SlimRequest($method, $uri, $h, $cookies, $serverParams, $stream);
    }

    /**
     * @throws Exception
     */
    protected function getAppInstance(): App
    {
        // Instantiate PHP-DI ContainerBuilder
        $containerBuilder = new ContainerBuilder();

        // Container intentionally not compiled for tests.
        $containerDef = require __DIR__ . '/../config/container.php';
        $containerBuilder->addDefinitions($containerDef);

        // Build PHP-DI Container instance
        $this->container = $containerBuilder->build();

        // Instantiate the app
        AppFactory::setContainer($this->container);
        $app = AppFactory::create();

        // Register middleware
        $middleware = require __DIR__ . '/../config/middleware.php';
        $middleware($app);

        // Register routes
        $routes = require __DIR__ . '/../config/routes.php';
        $routes($app);

        return $app;
    }

    protected function displayContent($content): void
    {
        try {
            file_put_contents("php://stderr", json_encode($content, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT));
        } catch (JsonException $e) {
        }
    }
}