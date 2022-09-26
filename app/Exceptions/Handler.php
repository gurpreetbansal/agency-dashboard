<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use App\Traits\ApiStatus;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Handler extends ExceptionHandler
{
    use ApiStatus;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {

        if ($request->route() <> null && $request->route()->getPrefix() == 'api') {   //add Accept: application/json in request

            if ($exception instanceof UnauthorizedHttpException) {

                if ($exception->getPrevious() instanceof TokenExpiredException) {
                    return $this->fail($exception->getMessage());
                }
                else if ($exception->getPrevious() instanceof TokenInvalidException) {
                    return $this->fail($exception->getMessage());
                }
                else if ($exception->getPrevious() instanceof TokenBlacklistedException) {
                    return $this->fail($exception->getMessage());
                }
            }
        }

        return parent::render($request, $exception);
    }

    
}
