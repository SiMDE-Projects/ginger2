<?php

namespace SIMDE\Ginger\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SIMDE\Ginger\Domain\Application\Service\ApplicationReaderService;
use SIMDE\Ginger\Exception\UnauthorizedException;

final class AuthMiddleware implements MiddlewareInterface
{
    /**
     * @var ApplicationReaderService
     */
    private ApplicationReaderService $applicationReaderService;

    public function __construct(ApplicationReaderService $applicationReaderService)
    {
        $this->applicationReaderService = $applicationReaderService;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $apiKey = $request->getParsedBody()['key'] ?? $request->getQueryParams()['key'];
        if (!$apiKey) {
            throw new UnauthorizedException('Api key is missing');
        }

        $app = $this->applicationReaderService->getApplicationByKey($apiKey);
        $request = $request->withAttribute('application', $app);

        // Everything is OK
        return $handler->handle($request);
    }
}
