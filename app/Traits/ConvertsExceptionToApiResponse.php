<?php 


namespace App\Traits;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\{JsonResponse, Request, Response};
use Illuminate\Support\{Arr, Str};
use Illuminate\Validation\{UnauthorizedException, ValidationException};
use Symfony\Component\HttpKernel\Exception\{HttpException, HttpExceptionInterface, NotFoundHttpException};
use Throwable;
use App\Dtos\ApiResponse;


trait ConvertsExceptionToApiResponse
{

    /**
     * ================================================================
     * RenderApiResponse()
     * ================================================================
     * Обрабатывает исключение и возвращает соответствующий JSON-ответ 
     * или HTML-ответ, если это требуется.
     *
     * @param Throwable $Exception 
     * @param Request $Request 
     * @return JsonResponse|Response 
     */
    public function RenderApiResponse(Throwable $Exception, Request $Request): JsonResponse|Response
    {
        $Exception = $this->PrepareApiException($Exception, $Request);

        if ($Response = $this->GetExceptionResponse($Exception, $Request)) 
        {
            return $Response;
        }

        if ($Exception instanceof HttpException)
        {
            return $this->ConvertHTTPExceptionToJsonResponse($Exception);
        }

        if ($this->ShouldRenderHtmlOnException())
        {
            return parent::render($Request, $Exception);
        }

        return ApiResponse::Create(500, 'Server Error', $this->ConvertExceptionToArray($Exception));
    }

    /**
     * ================================================================
     * PrepareApiException()
     * ================================================================
     * Подготавливает исключение для API, 
     * преобразуя его в соответствующий тип.
     *
     * @param Throwable $Exception
     * @param Request $Request 
     * @return Throwable 
     */
    protected function PrepareApiException(Throwable $Exception, Request $request): Throwable
    {
        return match (TRUE)
        {
            $Exception instanceof NotFoundHttpException, $Exception instanceof ModelNotFoundException => with(
                $Exception, function ($Exception) {
                    $Message = (string) with($Exception->getMessage(), function ($Message) {
                        return blank($Message) || Str::contains($Message, 'No Query Results For Model') ? 'Resource Not Found' : $Message;
                    });
                    return new NotFoundHttpException($Message, $Exception);
                }
            ),
            $Exception instanceof AuthenticationException => new HttpException(401, $Exception->getMessage(), $Exception),
            $Exception instanceof UnauthorizedException => new HttpException(403, $Exception->getMessage(), $Exception),
            default => $Exception,
        };
    }

    /**
     * ================================================================
     * GetExceptionResponse()
     * ================================================================
     * Возвращает JSON-ответ для исключения, 
     * если оно является ошибкой валидации.
     *
     * @param Throwable $Exception 
     * @param Request $Request 
     * @return JsonResponse|null
     */
    protected function GetExceptionResponse(Throwable $Exception, Request $Request): ?JsonResponse
    {
        if ($Exception instanceof ValidationException)
        {
            return ApiResponse::FromFailedValidation($Exception->validator, $Request);
        }

        if (!$Exception instanceof ValidationException)
        {
            return NULL;
        }

        $Response = $Exception->GetResponse();
        return $Response instanceof JsonResponse
               ? ApiResponse::FromJsonResponse($Response)
               : ApiResponse::Create($Response->getStatusCode(), 'An Error Occurred', ['content' => $Response->getContent()]);
    }

    /**
     * ================================================================
     * ConvertHTTPExceptionToJsonResponse()
     * ================================================================
     * Преобразует исключение HTTP в JSON-ответ с кодом состояния,
     *  сообщением и заголовками.
     *
     * @param HttpExceptionInterface $IException 
     * @return JsonResponse 
     */
    protected function ConvertHTTPExceptionToJsonResponse(HttpExceptionInterface $IException): JsonResponse
    {
        $StatusCode = $IException->getStatusCode();
        $Message = $IException->getMessage();
        $Headers = $IException->getHeaders();
        $Data = method_exists($IException, 'getErrorData') ? call_user_func([$IException, 'getErrorData']) : NULL;
        return ApiResponse::Create($StatusCode, $Message, $Data, $Headers);
    }

    /**
     * ================================================================
     * ConvertExceptionToArray()
     * ================================================================
     * Преобразует исключение в массив, 
     * содержащий детали исключения, если включён режим отладки.
     *
     * @param Throwable $Exception 
     * @return array 
     */
    protected function ConvertExceptionToArray(Throwable $Exception)
    {
        return config('app.debug') ? [
            'Message' => $Exception->getMessage(),
            'Code' => $Exception->getCode(),
            'Exception' => get_class($Exception),
            'File' => $Exception->getFile(),
            'Line' => $Exception->getLine(),
            'Trace' => collect($Exception->getTrace())->map(fn ($Trace) => Arr::except($Trace, ['args']))->all(),
        ] : [];
    }

    /**
     * ================================================================
     * ShouldRenderHtmlOnException()
     * ================================================================
     * Проверяет, нужно ли рендерить HTML-ответ 
     * при возникновении исключения.
     *
     * @return bool 
     */
    protected function ShouldRenderHtmlOnException(): bool
    {   
        return (bool) config('api-response.render_html_on_exception');
    }
}