<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Domain\TravelOrder\Exceptions\TravelOrderException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class Handler extends ExceptionHandler
{
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof AuthorizationException) {
            return response()->json([
                'message' => 'Acesso negado'
            ], 403);
        }

        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'message' => 'Recurso não encontrado'
            ], 404);
        }

        if ($exception instanceof TravelOrderException) {
            Log::warning($exception->getMessage(), [
                'type' => get_class($exception),
            ]);

            return response()->json([
                'message' => $exception->getMessage()
            ], 422);
        }

        Log::error($exception->getMessage(), [
            'trace' => $exception->getTraceAsString(),
        ]);

        return response()->json([
            'message' => 'Erro interno do servidor'
        ], 500);
    }
}
