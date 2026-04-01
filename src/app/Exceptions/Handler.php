<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Domain\TravelOrder\Exceptions\TravelOrderException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;


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
        // Força resposta JSON para requisições de API
        if ($this->isApiRequest($request)) {
            return $this->handleApiException($request, $exception);
        }

        return parent::render($request, $exception);
    }

    private function isApiRequest($request): bool
    {
        // Se é rota de API
        if ($request->is('api/*')) {
            return true;
        }

        // Se pediu JSON explicitamente
        if ($request->wantsJson()) {
            return true;
        }

        // Se header Accept é JSON
        if ($request->header('Accept') === 'application/json') {
            return true;
        }

        // Se é rota /docs com api-docs.json
        if ($request->path() === 'docs') {
            return true;
        }

        return false;
    }

    private function handleApiException($request, Throwable $exception)
    {
        // 🔒 Autorização (Gate / Policy) - Ambas as variações
        if ($exception instanceof AuthorizationException || $exception instanceof AccessDeniedHttpException) {
            return response()->json([
                'message' => 'Acesso negado. Você não tem permissão para realizar esta ação.'
            ], 403);
        }

        // ✅ Validação
        if ($exception instanceof ValidationException) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $exception->errors()
            ], 422);
        }

        // 📦 Recurso não encontrado (404)
        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'message' => 'Recurso não encontrado'
            ], 404);
        }

        // 🧠 Domínio (suas regras de negócio)
        if ($exception instanceof TravelOrderException) {
            Log::warning($exception->getMessage(), [
                'type' => get_class($exception),
            ]);

            return response()->json([
                'message' => $exception->getMessage()
            ], 422);
        }

        // 🌐 HTTP Exceptions (401, 403, 500, etc)
        if ($exception instanceof HttpException) {
            return response()->json([
                'message' => $exception->getMessage() ?: $this->getHttpMessage($exception->getStatusCode())
            ], $exception->getStatusCode());
        }

        // 💥 Fallback (erro inesperado)
        Log::error($exception->getMessage(), [
            'trace' => $exception->getTraceAsString(),
        ]);

        return response()->json([
            'message' => 'Erro interno do servidor'
        ], 500);
    }

    private function getHttpMessage(int $statusCode): string
    {
        $messages = [
            400 => 'Requisição inválida',
            401 => 'Não autenticado',
            403 => 'Acesso negado',
            404 => 'Não encontrado',
            405 => 'Método não permitido',
            422 => 'Erro de validação',
            429 => 'Muitas requisições',
            500 => 'Erro interno do servidor',
        ];

        return $messages[$statusCode] ?? 'Erro do servidor';
    }
}
