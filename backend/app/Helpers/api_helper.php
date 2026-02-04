<?php

use Illuminate\Http\JsonResponse;

if (!function_exists('apiResponse')) {
    /**
     * Main method to return standard API response.
     *
     * @param mixed $data Data to be returned (null, array, object, collection)
     * @param string $message Message for the user
     * @param bool $success Operation status (true/false)
     * @param int $status HTTP status code (e.g. 200, 400, 404, 500)
     * @return JsonResponse
     */
    function apiResponse($data = null, string $message = '', bool $success = true, int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data'    => $data,
        ], $status);
    }
}
