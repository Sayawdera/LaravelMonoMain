<?php 


namespace App\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Http\Resources\Json\{JsonResource, ResourceCollection};
use Throwable;
use App\Dtos\ApiResponse;

trait RendersApiResponse
{
     /**
     * ================================================================
     * ResponseOK()
     * ================================================================
     * Возвращает успешный JSON-ответ с кодом 200 (OK).
     *
     * @param string $Message
     * @param array|string|null $Data 
     * @param array $Headers 
     * @return JsonResponse 
     */
    public function ResponseOK(string $Message, array|string $Data = null, array $Headers = []): JsonResponse
    {
        return $this->SuccessResponse($Message, $Data, headers: $Headers);
    }
    
    /**
     * ================================================================
     * CreateResponse()
     * ================================================================
     * Возвращает успешный JSON-ответ с кодом 201 (Создано).
     *
     * @param string $Message 
     * @param array|string|null $Data 
     * @param array $Headers 
     * @return JsonResponse 
     */
    public function CreateResponse(string $Message, array|string $Data = null, array $Headers = []): JsonResponse
    {
        return $this->SuccessResponse($Message, $Data, 201, $Headers);
    }

    /**
     * ================================================================
     * AcceptedResponse()
     * ================================================================
     * Возвращает успешный JSON-ответ с кодом 202 (Принято).
     *
     * @param string $Message 
     * @param array|string|null $Data 
     * @param array $Headers 
     * @return JsonResponse 
     */
    public function AcceptedResponse(string $Message, array|string $Data = null, array $Headers = []): JsonResponse
    {
        return $this->SuccessResponse($Message, $Data, 202, $Headers);
    }

    /**
     * ================================================================
     * NoContentResponse()
     * ================================================================
     * Возвращает успешный JSON-ответ с кодом 204 (Нет содержимого).
     *
     * @return JsonResponse 
     */
    public function NoContentResponse(): JsonResponse
    {
        return $this->SuccessResponse('', NULL, 204);
    }

    /**
     * ================================================================
     * SuccessResponse()
     * ================================================================
     * Универсальный метод для создания успешного JSON-ответа.
     *
     * @param string $message
     * @param array|string|null $data 
     * @param int $status
     * @param array $headers 
     * @return JsonResponse
     */
    public function SuccessResponse(string $message, array|string $data = null, int $status = 200, array $headers = []): JsonResponse
    {
        return ApiResponse::Create($status, $message, $data, $headers);
    }

    /**
     * ================================================================
     * ResourceResponse()
     * ================================================================
     * Возвращает JSON-ответ с указанным ресурсом, сообщением и заголовками.
     *
     * @param JsonResource $Resource
     * @param string $Message
     * @param int $Status 
     * @param array $Headers
     * @return JsonResponse 
     */
    public function ResourceResponse(JsonResource $Resource, string $Message, int $Status = 200, array $Headers = []): JsonResponse
    {
        if (!$Resource instanceof ResourceCollection && blank($Resource->with) && blank($Resource->additional))
        {
            return ApiResponse::Create($Status, $Message, $Resource, $Headers);
        }
        $Response = $Resource->response()->withHeaders($Headers)->setStatusCode($Status);
        return APiResponse::FromJsonResponse($Response, $Message, TRUE);
    }
    /**
     * ================================================================
     * ResourceCollectionResponse()
     * ================================================================
     * Возвращает JSON-ответ с коллекцией ресурсов, 
     * включая сообщение и дополнительные заголовки.
     *================================================================
     *
     * @param ResourceCollection $Collection
     * @param string $Message 
     * @param bool $Wrap
     * @param int $Status 
     * @param array $Headers 
     * @return JsonResponse
     */
    public function ResourceCollectionResponse(ResourceCollection $Collection, string $Message, bool $Wrap = true, int $Status = 200, array $Headers = []): JsonResponse
    {
        $Response = $Collection->response()->withHeaders($Headers)->setStatusCode($Status);
        return ApiResponse::FromJsonResponse($Response, $Message, $Wrap);
    }

    /**
     * ================================================================
     * UnAuthenticatedResponse()
     * ================================================================
     * Возвращает JSON-ответ с сообщением о неаутентифицированном запросе.
     *
     * @param string $Message 
     * @return JsonResponse 
     */
    public function UnAuthenticatedResponse(string $Message): JsonResponse
    {
        return $this->ClientErrorResponse($Message, 401);
    }

    /**
     * ================================================================
     * BadRequestResponse()
     * ================================================================
     * Возвращает JSON-ответ с сообщением о неверном запросе.
     *
     * @param string $Message
     * @param array|null $Error
     * @return JsonResponse 
     */
    public function BadRequestResponse(string $Message, ?array $Error = null): JsonResponse
    {
        return $this->ClientErrorResponse($Message, 400, $Error);
    }

    /**
     * ================================================================
     * ForbidenResponse()
     * ================================================================
     * Возвращает JSON-ответ с сообщением о запрете доступа.
     *
     * @param string $Message 
     * @param array|null $Error 
     * @return JsonResponse 
     */
    public function ForbidenResponse(string $Message, ?array $Error = null): JsonResponse
    {
        return $this->ClientErrorResponse($Message, 403, $Error);
    }

    /**
     * ================================================================
     * NotFoundResponse()
     * ================================================================
     * Возвращает JSON-ответ с сообщением о ненайденном ресурсе.
     *
     * @param string $Message 
     * @param array|null $Error 
     * @return JsonResponse 
     */
    public function NotFoundResponse(string $Message, ?array $Error = null): JsonResponse
    {
        return $this->ClientErrorResponse($Message, 404, $Error);
    }

    /**
     * ================================================================
     * ThrowValidationErrorExceptionWhen()
     * ================================================================
     * Бросает исключение валидации, если условие выполняется.
     *
     * @param mixed $Condition 
     * @param array $Messages 
     * @throws ValidationException
     */
    public function ThrowValidationErrorExceptionWhen(mixed $Condition, array $Messages): void
    {
        if ((bool) $Condition)
        {
            throw ValidationException::withMessages($Messages);
        }
    }

    /**
     * ================================================================
     * ValidationFailedResponse()
     * ================================================================
     * Возвращает JSON-ответ с сообщением об ошибке валидации.
     *
     * @param Validator $Validator
     * @param Request|null $Reques
     * @param string|null $Message 
     * @return JsonResponse 
     */
    public function ValidationFailedResponse(Validator $Validator, ?Request $Reques = null, ?string $Message = null): JsonResponse
    {
        return ApiResponse::FromFailedValidation($Validator, $Reques ?? request(), $Message);
    }

    /**
     * ================================================================
     * ClientErrorResponse()
     * ================================================================
     * Возвращает JSON-ответ с сообщением об ошибке на стороне клиента.
     *
     * @param string $Message
     * @param int $Status
     * @param array|null $Error 
     * @param array $Headers 
     * @return JsonResponse 
     */
    public function ClientErrorResponse(string $Message, int $Status = 400, ?array  $Error = null, array $Headers = []): JsonResponse
    {
        return ApiResponse::Create($Status, $Message, $Error, $Headers);
    }


    /**
     * ================================================================
     *  ServerErrorResponse()
     * ================================================================
     * 
     * Данная функция обрабатывает создание стандартизированного ответа 
     * об ошибке сервера в формате JSON.
     * Если передано исключение, оно будет зарегистрировано. 
     * Функция возвращает JSON-ответ с указанным кодом статуса и сообщением.
     * Описание функции ServerErrorResponse
     * ================================================================
     * @param string $Message 
     * @param int $Status 
     * @param mixed $Exception 
     * @return JsonResponse 
     */
    public function ServerErrorResponse(string $Message, int $Status = 500, ?Throwable $Exception = null): JsonResponse
    {
        if ($Exception !== null)
        {
            report($Exception);
        }
        return ApiResponse::create($Status, $Message ?: $Exception?->getMessage());
    }
}