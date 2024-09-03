<?php


namespace App\Interfaces;

use Closure;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Contracts\Validation\Validator;
interface IApiResponse
{
    /**
     * @param Closure|null $Formatter
     * @return void
     */
    public static function RegisterValidationErrorFormatter(?Closure $Formatter): void;

    /**
     * @param int $StatusCode
     * @param string|null $Message
     * @param $Data
     * @param array $Headers
     * @return JsonResponse
     */
    public static function  Create(int $StatusCode, string $Message= null, $Data = null, array $Headers = []): JsonResponse;

    /**
     * @param JsonResponse $Response
     * @param string|NULL $Message
     * @param bool $Wrap
     * @return JsonResponse
     */
    public static function FromJsonResponse(JsonResponse $Response, string $Message = NULL, bool $Wrap = FALSE): JsonResponse;

    /**
     * @param Validator $Validator
     * @param Request|null $Request
     * @param string|null $Message
     * @return JsonResponse
     */
    public static function FromFailedValidation(Validator $Validator, ?Request $Request = NULL, ?string $Message = NULL): JsonResponse;

    /**
     * @param mixed $Data
     * @param bool $Success
     * @return JsonResponse
     */
    public static function Success(mixed $Data, bool $Success): JsonResponse;

    /**
     * @param $Message
     * @param int $Status
     * @param bool $Success
     * @param array $Headers
     * @param bool $isArray
     * @return JsonResponse
     */
    public static function Error($Message, int $Status, bool $Success, array $Headers, bool $isArray = false): JsonResponse;
}
