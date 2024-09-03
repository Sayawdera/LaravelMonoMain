<?php


namespace App\Traits;


use InvalidArgumentException;
use JsonSerializable;
use Illuminate\Contracts\Support\{Arrayable, Jsonable};
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\{Arr, Str};
use App\Dtos\ApiResponse;
use stdClass;
use Symfony\Component\HttpFoundation\Response as Status;

trait ApiHelpers
{
    /**
     * @var ApiResponse
     */
    protected ApiResponse $apiResponse;

    /**
     * ================================================================
     * NormalizedData()
     * ================================================================
     * Нормализует данные, преобразуя их в массив или другой тип данных.
     *
     * @param mixed $Data
     * @return mixed 
     */
    protected function NormalizedData(mixed $Data)
    {
        if (is_array($Data) || is_null($Data))
        {
            return $Data;
        }

        return match (TRUE)
        {
            $Data instanceof JsonResource => $Data->resolve(),
            $Data instanceof Jsonable => json_decode($Data->toJson(), true),
            $Data instanceof JsonSerializable => $Data->jsonSerialize() ,
            $Data instanceof Arrayable => $Data->toArray(),
            $Data instanceof stdClass => (array) $Data,
            default => $Data
        };
    }

    /**
     * ================================================================
     * GetDataWrapper()
     * ================================================================
     * Получает обёртку для данных, если нужно их обернуть.
     *
     * @return string|null 
     */
    protected function GetDataWrapper(): ?string
    {
        if (! $this->apiResponse->ShouldWrapResponse)
        {
            return NULL;
        }
        return collect(config('api-response.data_wrappers'))->first( fn ($Value, $Key) => Str::is(Str::of($Key)->replace('x', '*'), $this->apiResponse->StatusCode));
    }

    /**
     * ================================================================
     * SetStatusCode()
     * ================================================================
     * Устанавливает код состояния HTTP-ответа.
     *
     * @param int $StatusCode
     * @return void
     * @throws InvalidArgumentException 
     */
    protected function SetStatusCode(int $StatusCode): void
    {
        if (! array_key_exists($StatusCode, JsonResponse::$statusTexts))
        {
            throw new InvalidArgumentException("Invalid HTTP Status COde: [{$StatusCode}]");
        }
        $this->StatusCode = $StatusCode;
    }

    /**
     * ================================================================
     * PullErrorCodeFromData()
     * ================================================================
     * Извлекает код ошибки из данных, если он присутствует.
     *
     * @param array $Data 
     * @param string $Message
     * @param string|null $TranslatedKey 
     * @return array 
     */
    protected function PullErrorCodeFromData(array $Data, string $Message, ?string $TranslatedKey = NULL)
    {
        if (array_key_exists('error_code', $Data))
        {
            return ['error_code' => (string) Arr::pull($Data, 'error_code')];
        }

        if (!is_null($TranslatedKey) && Str::contains($Message, 'error_code'))
        {
            return ['error_code' => $TranslatedKey];
        }
        return [];
    }

    /**
     * ================================================================
     * PrepareResponseData()
     * ================================================================
     * Подготавливает данные для ответа, нормализуя их и добавляя сообщения.
     *
     * @return array|null
     */
    protected function PrepareResponseData(): ?array
    {
        $SuccessFull = $this->apiResponse->StatusCode >= 200 && $this->apiResponse->StatusCode < 300;
        $NormalizeData = $this->NormalizedData($this->apiResponse->Data);
        $Data = is_array($NormalizeData) ? $NormalizeData : [];
        $MessageData = $this->GetTranslatedMessageData($this->apiResponse->Message, $Data, $SuccessFull);
        $NormalizeData =is_array($NormalizeData) ? $Data : $NormalizeData;
        $ResponseData = [
            'Success' => $SuccessFull,
            'Message' => $MessageData['Message'] ?? $this->apiResponse->Message,
        ];
        $ResponseData += Arr::except($MessageData, ['Key', 'Message']);

        if ($this->apiResponse->ShouldWrapResponse && filled($NormalizeData))
        {
            $ResponseData[$this->GetDataWrapper()] = $NormalizeData;
        } else if (!is_null($NormalizeData)) {
            $ResponseData += $ResponseData;
        }
        return $ResponseData;
    }

    /**
     * ================================================================
     * GetTranslatedMessageData()
     * ================================================================
     * Получает переведённые данные сообщения в зависимости от успешности операции.
     *
     * @param string $Message
     * @param array $Data 
     * @param bool $SuccessFull
     * @return array
     */
    protected function GetTranslatedMessageData(string $Message, array $Data, bool $SuccessFull): array
    {
        $FileKey = $SuccessFull ? 'Success' : 'Errors';
        $File = config("api-response.translation.{$FileKey}");

        if (!is_string($File))
        {
            return [];
        }
        $TranslationPrefix = $this->IsTranslationKeys($Message) ? NULL : 'api-response::'.config("api-response.translation.{$FileKey}");
        $Translated = $this->ExtractTranslationDataFromResponsePayload($Data, $Message, $TranslationPrefix);

        if ($SuccessFull)
        {
            return (array) $Translated;
        }
        return array_merge($Translated, $this->PullErrorCodeFromData($Data, $Message, $Translated['key']));
    }

    /**
     * ================================================================
     * ExtractTranslationDataFromResponsePayload()
     * ================================================================
     * Извлекает данные перевода из полезной нагрузки ответа.
     *
     * @param array $Data
     * @param string $Message 
     * @param string|null $Prefix 
     * @return array
     */
    protected function ExtractTranslationDataFromResponsePayload(array $Data, string $Message, ?string $Prefix = null)
    {
        $Parameters = $this->ParseStringToTranslationParameters($Message);
        $Attributes = array_merge($Parameters['attributes'], Arr::pull($Data, '_attributes', []));
        return $this->GetTranslatedStringArray($Parameters['name'], $Attributes, $Prefix);
    }

    /**
     * ================================================================
     * GromCallingResponse()
     * ================================================================
     * Формирует JSON-ответ для API с указанными параметрами.
     *
     * @param string|array $Message
     * @param bool $Success
     * @param int $Status
     * @param array $Headers
     * 
     * @return JsonResponse 
     */
    protected static function GromCallingResponse(string|array $Message, bool $Success = TRUE, int $Status = Status::HTTP_OK, array $Headers = [])
    {
        return  Response::json(["message" => $Message, "success" => $Success,], $Status, $Headers);
    }

    /**
     * ================================================================
     * SetData()
     * ================================================================
     * Устанавливает данные для ответа.
     *
     * @param mixed $Data 
     * @return $this
     */
    public function SetData(mixed $Data): static
    {
        return tap($this, fn (self $response) => $response->Data = $Data);
    }

    /**
     * ================================================================
     * IgnoreDataWrapper()
     * ================================================================
     * Игнорирует обёртку данных в ответе.
     *
     * @return $this 
     */
    public function IgnoreDataWrapper(): static
    {
        return tap($this, fn (self $response) => $response->apiResponse->ShouldWrapResponse = FALSE);
    }

    /**
     * ================================================================
     * Make()
     * ================================================================
     * Создаёт JSON-ответ на основе подготовленных данных и кода состояния.
     *
     * @return JsonResponse
     */
    public function Make(): JsonResponse
    {
        $StatusWithNoCOntent = config('api_response.http_statuses_with_no_content');
        $Data = in_array($this->apiResponse->StatusCode, $StatusWithNoCOntent) ? NULL : $this->PrepareResponseData();
        return new JsonResponse($Data, $this->apiResponse->StatusCode, $this->apiResponse->Headers);
    }

    


}
