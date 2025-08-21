<?php
namespace App\Helpers;

class ApiResponse
{
   public static function apiResponse( $status = 200, $message = null,$data = null)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $status);
    }
}
