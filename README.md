<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>


## About Laravel Monolith Structure Starter Main

![Created By](https://img.shields.io/badge/Created%20By-Al%20Ayubi-brightgreen)
![Powered By](https://img.shields.io/badge/Powered%20By-Al%20Ansar-blue)

Laravel  | Laravel Monolith API 
:---------|:----------------------
 11.x     | 2.x

 ## Usage

### Использование Traits


- Trait `App\Traits\RendersApiResponse`, которая может быть импортирована в ваш (базовый) класс контроллера, класс промежуточного программного обеспечения или даже в ваш класс обработчика исключений.
- Trait `App\Traits\ConvertsExceptionToApiResponse`, которую следует импортировать только в ваш класс обработчика исключений.

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Traits\RendersApiResponse;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests, RendersApiResponse;
}
```

Или какой-нибудь случайный класс контроллера:

```php
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\RendersApiResponse;

class RandomController extends Controller
{
    use RendersApiResponse;
}
```
В любом случае, у вас есть доступ к массе методов, которые вы можете вызвать для рендеринга ваших данных. Это включает:

```php
// Successful Responses
return $this->ResponseOK('This is a random message', $data = null, $headers = []);
return $this->CreateResponse('This is a random message', $data = null, $headers = []);
return $this->AcceptedResponse($message, $data, $headers);
return $this->NoContentResponse();
return $this->SuccessResponse($message, $data = null, $status = 200, $headers = []);

// Successful Responses for \Illuminate\Http\Resources\Json\JsonResource
return $this->ResourceResponse($jsonResource, $message, $status = 200, $headers = []);
return $this->ResourceCollectionResponse($resourceCollection, $message, $wrap = true, $status = 200, $headers = []);

// Error Responses
return $this->UnAuthenticatedResponse('Unauthenticated message');
return $this->BadRequestResponse('Bad request error message', $error = null);
return $this->ForbidenResponse($message);
return $this->NotFoundResponse($message);
return $this->ClientErrorResponse($message, $status = 400, $error = null, $headers = []);
return $this->ServerErrorResponse($message);
return $this->ValidationFailedResponse($validator, $request = null, $message = null);

$messages = ['name' => 'Name is not valid'];
$this->ThrowValidationErrorExceptionWhen($condition, $messages);
```

Также для обработки исключений, преобразования их в ответ API, используйте `App\Traits\ConvertsExceptionToApiResponse` трейт в вашем обработчике исключений, который предоставляет `RenderApiResponse` открытый метод, и это можно использовать следующим образом:

```php
<?php

namespace App\Exceptions;

use App\Traits\HandleApiException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Traits\ConvertsExceptionToApiResponse;
use Throwable;

class Handler extends ExceptionHandler
{
    use ConvertsExceptionToApiResponse;

    public function render($request, Throwable $e)
    {
        return $this->RenderApiResponse($e, $request);
    }
}
```

Вы также можете использовать `Renderable` метод класса-обработчика:

```php
<?php

namespace App\Exceptions;

use App\Traits\HandleApiException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Tratis\ConvertsExceptionToApiResponse;
use Throwable;

class Handler extends ExceptionHandler
{
    use ConvertsExceptionToApiResponse;

    public function register()
    {
        $this->Renderable(function (Throwable $e, $request) {
            return $this->RenderApiResponse($e, $request);
        });
    }
}
```

### Использование Статических методов Sucess(); и Error();

Так же бывает что ваш метод должен дать четкий ответ по типу Success или Error и в классе `ApiResponse` существует два статических класса `ApiResponse::Success();` и `ApiResponse::Error();` что бы вызывать ответ Success вы должны вторым параметром передавать `TRUE` второым параметром передается логический аргумент `bool $Success` ниже я предоставляю один из своиъ методов для регистрации где вызывается два статических методов

```php

 public function AnyMethod(array|string $data): JsonResponse
{
        
    return ApiResponse::Error("The email is not verified, please repeat again ", Response::HTTP_UNAUTHORIZED);
}


```
если коротко то вот 
```php

ApiResponse::Error("The email is not verified, please repeat again ", Response::HTTP_UNAUTHORIZED);

```
и в случае ответа Sucess();
```php

return ApiResponse::Success(array $data, bool $Sucess TRUE);
return ApiResponse::Error(array $data, int $Status = Response::HTTP_OK, bool $Sucess TRUE);
```
по дефолту параметр $Success стоит FALSE 



