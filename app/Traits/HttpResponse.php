<?php

namespace App\Traits;

trait HttpResponse
{
    public function fail( $status, $statusCode, $message, $data ){
        return response()->json([
            'status' => $status,
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    public function success( $status, $statusCode, $message, $data ){
        return response()->json([
            'status' => $status,
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data,
        ], $statusCode );
    }
}
