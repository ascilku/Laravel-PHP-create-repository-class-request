<?php

namespace App\Helpers;

/**
 * Format response.
 */
class ResponseFormatter
{
    /**
     * API Response
     *
     * @var array
     */
    protected static $response = [
        'meta' => [
            'code' => 200,
            'status' => 'success',
            'message' => null,
        ],
        'data' => null,
    ];

    /**
     * Give success response.
     */
    // public static function success($data = null, $message = null)
    // {
    //     self::$response['meta']['message'] = $message;
    //     self::$response['data'] = $data;

    //     return response()->json(self::$response, self::$response['meta']['code']);
    // }

    /**
     * Give error response.
     */
    public static function responFormatter($status = null, $code = null, $message = null, $data = null)
    {
        self::$response['meta']['status'] = $status;
        self::$response['meta']['code'] = $code;
        self::$response['meta']['message'] = $message;
        self::$response['data'] = $data;

        return response()->json(self::$response, self::$response['meta']['code']);
    }

    
}