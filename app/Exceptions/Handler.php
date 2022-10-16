<?php

namespace App\Exceptions;

use App\Helpers\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\Exceptions\OAuthServerException as PassportOAuthServerException;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Log\LogLevel;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<Throwable>, LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $exception
     * @return JsonResponse
     *
     * @throws Throwable
     */
    public function render(
        $request,
        Throwable $exception
    ): JsonResponse {
        $data = null;
        $code = $exception->getCode();
        $message = $exception->getMessage();

        if ($exception instanceof HttpException) {
            $code = $exception->getStatusCode();
        }

        if ($exception instanceof ValidationException) {
            $code = 422;
            $data = $exception->errors();
            $message = 'Dados Inválidos';
        }

        if ($exception instanceof NotFoundHttpException && empty($message)) {
            $code = 404;
            $message = 'Não encontrado';
        }

        if (!is_numeric($code) || $code < 200 || $code > 500) {
            $code = 500;
            $data = $exception->getTrace();

            if (!env('APP_DEBUG')) {
                $data = null;
                $message = 'Erro interno no servidor';
            }
        }

        $response = [
            'error' => true,
            'message' => $message
        ];

        if ($data != null) {
            $response = array_merge($response, ['data' => $data]);
        }

        return response()->json($response, $code);
    }
}
