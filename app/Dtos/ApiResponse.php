<?php


namespace App\Dtos;

use Closure;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\{Arr, Str};
use Illuminate\Support\Traits\{Conditionable, Macroable};
use App\Traits\{RendersApiResponse, ConvertsExceptionToApiResponse, Translatable, ApiHelpers};
use Symfony\Component\HttpFoundation\Response as Status;
use App\Interfaces\IApiResponse;

class ApiResponse implements IApiResponse
{
    use Conditionable, Translatable, Macroable, ConvertsExceptionToApiResponse, RendersApiResponse, ApiHelpers;

    protected mixed $Data;
    protected ?string $Message;
    protected int $StatusCode;
    protected array $Headers;
    protected bool $ShouldWrapResponse = TRUE;
    protected ?Closure $ValidationErrorFormatter = null;

    public function __construct(int $StatusCode, string $Message = null, mixed $Data = null, array $Headers = [])
    {
        $this->Message= $Message;
        $this->Data= $Data;
        $this->Headers = $Headers;

        $this->SetStatusCode($StatusCode);
    }

    /**
     * ================================================================
     * RegisterValidationErrorFormatter()
     * ================================================================
     * Регистрирует форматтер для ошибок валидации.
     *
     * @param Closure|null $Formatter
     * @return void
     */
    public static function RegisterValidationErrorFormatter(?Closure $Formatter): void
    {
        static::$ValidationErrorFormatter = $Formatter;
    }

    /**
     * ================================================================
     * Create()
     * ================================================================
     * Создаёт новый экземпляр класса и возвращает JSON-ответ.
     *
     * @param int $StatusCode
     * @param string|null $Message
     * @param mixed $Data 
     * @param array $Headers 
     * @return JsonResponse 
     */
    public static function Create(int $StatusCode, string $Message= null, $Data = null, array $Headers = []): JsonResponse
    {
        return (new static($StatusCode, $Message, $Data, $Headers))->Make();
    }

    /**
     * ================================================================
     * FromJsonResponse()
     * ================================================================
     * Создаёт экземпляр класса из существующего JSON-ответа.
     *
     * @param JsonResponse $Response 
     * @param string|null $Message 
     * @param bool $Wrap 
     * @return JsonResponse .
     */
    public static function FromJsonResponse(JsonResponse $Response, string $Message = NULL, bool $Wrap = FALSE): JsonResponse
    {
        $Data = $Response->getData(TRUE);
        $Status = $Response->status();
        $ResponseData = is_array($Data) ? $Data : ['Message_data' => $Data];
        $Message = (string) ($Message ?: Arr::pull($ResponseData, 'Message', JsonResponse::$statusTexts[$Status]));
        $Response = new static($Status, $Message, $ResponseData, $Response->headers->all());
        return $Response->unless($Wrap, fn (self $Response) => $Response->IgnoreDataWrapper()->Make());
    }

    /**
     * ================================================================
     * FromFailedValidation()
     * ================================================================
     * Создаёт ответ для неудачной валидации.
     *
     * @param Validator $Validator 
     * @param Request|null $Request 
     * @param string|null $Message
     * @return JsonResponse 
     */
    public static function FromFailedValidation(Validator $Validator, ?Request $Request = NULL, ?string $Message = NULL): JsonResponse
    {
        ['Code' => $Code, 'Message' => $DefaultMessage] = config('api-response.validation');
        $Response = new static($Code, $Message ?? $DefaultMessage);
        $Errors = $Response->GetValidationErrors($Validator);
        return $Response->SetData($Errors)->Make();
    }

    /**
     * ================================================================
     * Success()
     * ================================================================
     * Создаёт успешный JSON-ответ.
     *
     * @param mixed $Data
     * @return JsonResponse 
     */
    public static function Success(mixed $Data, bool $Success): JsonResponse
    {
        return self::GromCallingResponse($Data, $Success);
    }

    /**
     * ================================================================
     * Error()
     * ================================================================
     * Создаёт JSON-ответ с ошибкой.
     *
     * @param string $message
     * @param int $status
     * @param bool $isArray 
     * @return JsonResponse
     */
    public static function Error($Message, int $Status = Status::HTTP_OK, bool $Success = FALSE, array $Headers = [], bool $isArray = false): JsonResponse
    {
        if ($isArray) 
        {
            $Message = reset($Message)[0];
        }
        return self::GromCallingResponse($Message, $Status, $Success, $Headers);
    }
}
