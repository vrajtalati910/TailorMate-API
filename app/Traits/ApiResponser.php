<?php

namespace App\Traits;

trait ApiResponser
{
    static function errorResponse($message, $status_code = 200)
    {
        return response()->json([
            'message' => $message,
            'status' => '0',
        ], $status_code);
    }
}
