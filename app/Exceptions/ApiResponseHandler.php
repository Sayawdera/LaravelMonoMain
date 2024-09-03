<?php

namespace App\Exceptions;

use Exception;
use App\Traits\HandleApiException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Traits\ConvertsExceptionToApiResponse;
use Throwable;
class ApiResponseHandler extends Exception
{
    use ConvertsExceptionToApiResponse;

    public function render($request, Throwable $e)
    {
        return $this->RenderApiResponse($e, $request);
    }
}
