<?php
namespace Api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Router\RouteResult;

class ApiMiddleware
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $result = $request->getAttribute(RouteResult::class, false);

        // if no route exists and url starts with /api, return 404 json response
        if (!$result instanceof RouteResult && strpos($request->getUri()->getPath(), '/api') === 0) {
            return new JsonResponse(['message' => 'Not found'], 404, $response->getHeaders(), JSON_PRETTY_PRINT);
        }

        return $next($request, $response);
    }
}
