<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Validation\ValidationException;
use Exception;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
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

    // format validation
    protected static $response = [
        'meta' => [
            'code' => 422,
            'status' => 'Failed',
            'message' => "Failed Data Validation",
        ],
        'data' => null,
    ];

    protected function invalidJson($request, ValidationException  $exception)
    {
        // $data = 
            // 'code' => $exception->status,
            // 'message' => $exception->getMessage(),
            // 'errors' => $exception->errors()
            // $exception->errors();
        

        // self::$response['meta']['message'] = false;
        self::$response['data'] = $exception->errors();

        // You can return json response with your custom form
        // return response()->json([
        //     'success' => false,
            
        // ], $exception->status);

        return response()->json(self::$response, self::$response['meta']['code']);
    }

    public function render($request, Throwable $exception)
    {
        if ($request->wantsJson()) {  
            return $this->handleApiException($request, $exception);
        } else {
            $retval = parent::render($request, $exception);
        }

        return $retval;
    }

    private function handleApiException($request, Throwable $exception)
    {
        $exception = $this->prepareException($exception);

        if ($exception instanceof \Illuminate\Http\Exception\HttpResponseException) {
            $exception = $exception->getResponse();
        }

        if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
            $exception = $this->unauthenticated($request, $exception);
        }

        if ($exception instanceof \Illuminate\Validation\ValidationException) {
            $exception = $this->convertValidationExceptionToResponse($exception, $request);
        }

        return $this->customApiResponse($exception);
    }

    private function customApiResponse($exception)
    {
        if (method_exists($exception, 'getStatusCode')) {
            $statusCode = $exception->getStatusCode();
        } else {
            $statusCode = 500;
        }

        $response = [];

        switch ($statusCode) {
            case 401:
                $response['status'] = 'Unauthorized';
                $response['message'] = 'Terjadi Unauthorized Akses';
                break;
            case 403:
                $response['status'] = 'Forbidden';
                $response['message'] = 'Terjadi Forbidden Akses';
                break;
            case 404:
                $response['status'] = 'Not Found';
                $response['message'] = 'Terjadi Not Found Akses';
                break;
            case 500:
                $response['status'] = 'Method Not Allowed';
                $response['message'] = 'Terjadi Method Not Allowed Akses';
                break;
            case 422:
                $response['status'] = $exception->original['message'];
                $response['message'] = $exception->original['errors'];
                break;
            // default:
            //     $response['status'] = ($statusCode == 500) ? 'Whoops, looks like something went wrong' : $exception->getMessage();
            //     break;
        }

        if (config('app.debug')) {
            $data['trace'] = $exception->getTrace();
            $data['code'] = $exception->getCode();
        }

        // $response['status'] = $statusCode;

        $data['meta']['code'] = $statusCode;
        $data['meta']['status'] = $response['status'];
        $data['meta']['message'] = $response['message'] ;
        $data['data'] = null;

        return response()->json($data, $statusCode);
    }

    // protected function invalidJson($request, ValidationException  $exception)
    // {
    //     // You can return json response with your custom form
    //     return response()->json([
    //         'success' => false,
    //         'data' => [
    //             'code' => $exception->status,
    //             'message' => $exception->getMessage(),
    //             'errors' => $exception->errors()
    //         ]
    //     ], $exception->status);
    // }
}
