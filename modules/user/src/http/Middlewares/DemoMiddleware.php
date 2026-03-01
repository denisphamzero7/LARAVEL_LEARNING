<?php

namespace Modules\user\src\http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DemoMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (method_exists($response, 'setContent')) {
            $response->setContent('middleware - ' . $response->getContent());
        }

        return $response;
    }
}