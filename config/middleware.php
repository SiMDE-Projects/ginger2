<?php

use SIMDE\Ginger\Exception\AccountsException;
use SIMDE\Ginger\Exception\ForbiddenException;
use SIMDE\Ginger\Exception\UnauthorizedException;
use SIMDE\Ginger\Exception\UserNotFoundException;
use SIMDE\Ginger\Exception\ValidationException;
use SIMDE\Ginger\Exception\ApplicationNotFoundException;
use Slim\App;
use Slim\Exception\HttpNotFoundException;
use Slim\Middleware\ErrorMiddleware;
use SIMDE\Ginger\Middleware\AuthMiddleware;

return function (App $app) {
    // Parse json, form data and xml
    $app->addBodyParsingMiddleware();

    // Add the Slim built-in routing middleware
    $app->addRoutingMiddleware();
    
    $app->add(AuthMiddleware::class);
    // Custom error handler
    $customErrorHandler = function (
        Psr\Http\Message\ServerRequestInterface $request,
        Throwable                               $exception,
        bool                                    $displayErrorDetails,
        bool                                    $logErrors,
        bool                                    $logErrorDetails
    ) use($app) {
        $response = $app->getResponseFactory()->createResponse();
        $result = [
            "error" => [
                "code" => 500,
                "message" => "Uncaught exception"
            ]
        ];

        if ($exception instanceof ValidationException) {
            $result["error"]["message"] = $exception->getMessage();
            $result["error"]["code"] = 400;
        } elseif ($exception instanceof UserNotFoundException ||
            $exception instanceof HttpNotFoundException ||
            $exception instanceof ApplicationNotFoundException)
            {
            $result["error"]["message"] = $exception->getMessage();
            $result["error"]["code"] = 404;
        } elseif ($exception instanceof AccountsException) {
            $result["error"]["message"] = "Accounts exception";
            $result["error"]["code"] = 500;
        } elseif ($exception instanceof ForbiddenException) {
            $result["error"]["message"] = "Forbidden";
            $result["error"]["code"] = 403;
        } elseif ($exception instanceof UnauthorizedException) {
            $result["error"]["message"] = "Unauthorized";
            $result["error"]["code"] = 401;
        }

        if($displayErrorDetails) {
            $result["error"]["detail"]["message"] = $exception->getMessage();
            $result["error"]["detail"]["errcode"] = $exception->getCode();
            $result["error"]["detail"]["file"] = $exception->getFile();
            $result["error"]["detail"]["line"] = $exception->getLine();
            $result["error"]["detail"]["stack"] = $exception->getTrace();
        }

        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($result["error"]["code"]);
    };
    $errorMiddleware = $app->addErrorMiddleware(DETAILED_ERRORS, true, true);
    $errorMiddleware->setDefaultErrorHandler($customErrorHandler);

    // Add Error Middleware
    $app->add(ErrorMiddleware::class);
};
