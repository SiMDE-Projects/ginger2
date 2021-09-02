<?php

use Slim\App;
use Slim\Middleware\ErrorMiddleware;

return function (App $app) {
    // Parse json, form data and xml
    $app->addBodyParsingMiddleware();

    // Add the Slim built-in routing middleware
    $app->addRoutingMiddleware();

    // Custom error handler
    $customErrorHandler = function (
        Psr\Http\Message\ServerRequestInterface $request,
        \Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ) use($app) {
        $response = $app->getResponseFactory()->createResponse();
        $result = [
            "error" => [
                "code" => 500,
                "message" => "Uncaught exception"
            ]
        ];

        if ($exception instanceof \App\Exception\ValidationException) {
            $result["error"]["message"] = $exception->getMessage();
            $result["error"]["code"] = 400;
        } elseif ($exception instanceof \App\Exception\UserNotFoundException ||
            $exception instanceof \Slim\Exception\HttpNotFoundException)
            {
            $result["error"]["message"] = $exception->getMessage();
            $result["error"]["code"] = 404;
        } elseif ($exception instanceof \App\Exception\AccountsException) {
            $result["error"]["message"] = "Accounts exception";
            $result["error"]["code"] = 500;
        }

        if($displayErrorDetails) {
            $result["error"]["detail"]["message"] = $exception->getMessage();
            $result["error"]["detail"]["errcode"] = $exception->getCode();
            $result["error"]["detail"]["file"] = $exception->getFile();
            $result["error"]["detail"]["line"] = $exception->getLine();
            $result["error"]["detail"]["stack"] = $exception->getTrace();
        }

        $response->getBody()->write(json_encode($result));
        return $response->withStatus($result["error"]["code"]);
    };
    $errorMiddleware = $app->addErrorMiddleware("DETAILED_ERRORS", true, true);
    $errorMiddleware->setDefaultErrorHandler($customErrorHandler);

    // Add Error Middleware
    $app->add(ErrorMiddleware::class);
};
