<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function successResponse($data = [] , $status=200)
    {
        return $this->jsonResponse(true, 'Success', $data, $status, []);
    }

    public function failedResponse($data = [], $status = 500)
    {
        return $this->jsonResponse(false, 'Failed', $data, $status, []);
    }

    public function jsonResponse($status, $message, $data, $StatusCode, $headers = [])
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ], $StatusCode, $headers);
    }
}
