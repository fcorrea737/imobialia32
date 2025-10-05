<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;


use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
   ->withMiddleware(function (Middleware $middleware) {

        // Adiciona o middleware padrão de CORS do Laravel.
        $middleware->alias([
            'cors' => \Illuminate\Http\Middleware\HandleCors::class,
        ]);

        // Para configurar as opções de CORS, edite o arquivo config/cors.php conforme necessário.

        $middleware->group('api', [
            'cors',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
       // Intercepta ValidationException (erro 422)
        $exceptions->renderable(function (ValidationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => $e->getMessage(),
                    'error_code' => 'VALIDATION_FAILED',
                    'errors' => $e->errors(),
                ], 422);
            }
        });

        // Intercepta AuthenticationException (erro 401)
        $exceptions->renderable(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Unauthenticated.',
                    'error_code' => 'UNAUTHENTICATED',
                ], 401);
            }
        });

        // Intercepta HttpException (erros 403, 404, etc.)
        $exceptions->renderable(function (HttpException $e, Request $request) {
            if ($request->is('api/*')) {
                $errorCode = match ($e->getStatusCode()) {
                    403 => 'ACTION_UNAUTHORIZED',
                    404 => 'RESOURCE_NOT_FOUND',
                    default => 'HTTP_ERROR',
                };

                return response()->json([
                    'message' => $e->getMessage() ?: \Symfony\Component\HttpFoundation\Response::$statusTexts[$e->getStatusCode()],
                    'error_code' => $errorCode,
                ], $e->getStatusCode());
            }
        });
    })->create();
