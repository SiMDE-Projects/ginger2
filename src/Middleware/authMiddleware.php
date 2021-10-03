<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Exception\UnauthorizedException;
use App\Domain\Application\Service\ApplicationReaderService;

final class AuthMiddleware implements MiddlewareInterface
{
    /**
     * @var ApplicationReaderService
     */
    private $applicationReaderService;

    public function __construct(ApplicationReaderService $applicationReaderService)
    {
        $this->applicationReaderService = $applicationReaderService;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
      $apiKey = $request->getQueryParams()['key'];
      if (!$apiKey) {
          throw new UnauthorizedException('Api key is missing');
      }
      
      $app = $this->applicationReaderService->getApplicationByKey($apiKey);
      $request = $request->withAttribute('application', $app);
      
      // Everything is OK
      return $handler->handle($request);
    }
}
