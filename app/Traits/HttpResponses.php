<?php

namespace App\Traits;

trait HttpResponses
{
    protected function success(array|object $data, string $status, string $message = null, int $code = 200)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'StatusCode' => $code,
            'data' => $data,
        ], $code);
    }

    protected function error(string $status, string $message = null, int $code = 400, array|string $data = null)
    {
        if (!$data) {
            return response()->json([
                'status' => $status,
                'message' => $message,
                'StatusCode' => $code,
            ], $code);
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}
