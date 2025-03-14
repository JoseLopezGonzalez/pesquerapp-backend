<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /* Render */
    /* public function render($request, Throwable $exception)
    {
        // Si la solicitud espera JSON o es una API, forzar la respuesta JSON
        if ($request->expectsJson() || $request->is('api/*')) {

            // Manejar errores de validación
            if ($exception instanceof ValidationException) {
                return response()->json([
                    'message' => 'Error de validación.',
                    'errors' => $exception->errors(),
                ], 422); // 422 Unprocessable Entity
            }

            // Manejar errores de autenticación
            if ($exception instanceof AuthenticationException) {
                return response()->json([
                    'message' => 'No autenticado.',
                ], 401); // 401 Unauthorized
            }

            // Manejar errores HTTP estándar (404, 403, etc.)
            if ($exception instanceof HttpException) {
                return response()->json([
                    'message' => $exception->getMessage() ?: 'Error HTTP.',
                ], $exception->getStatusCode());
            }

            // Manejar cualquier otra excepción como error interno del servidor
            return response()->json([
                'message' => 'Ocurrió un error inesperado.',
                'error' => $exception->getMessage(),
            ], 500); // 500 Internal Server Error
        }

        // Si no es una API o no espera JSON, usar el manejo por defecto
        return parent::render($request, $exception);
    } */


    public function render($request, Throwable $exception)
    {
        return parent::render($request, $exception); // Esto muestra los errores normales en pantalla
    }

}
