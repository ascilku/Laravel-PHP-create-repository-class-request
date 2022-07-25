<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Validation\ValidationException;

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
