<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Info(
 *     description="Quizzes API",
 *     version="1.0.0",
 *     title="Demo Quizzes",
 *     @OA\Contact(
 *         email="huan.ld@neo-lab.vn"
 *     )
 * )
 */
/**
 * @OA\Server(
 *     description="Quizzes API",
 *     url=L5_SWAGGER_CONST_HOST
 * )
 */
/**
 * @OA\SecurityScheme(
 *    securityScheme="bearerAuth",
 *    type="http",
 *    scheme="bearer",
 *    bearerFormat="JWT"
 *),
 * )
 */

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function responseError($message= '', $errors = [], $status = 500, $headers = [])
    {
        if ($status === Response::HTTP_INTERNAL_SERVER_ERROR) {
            Log::error($errors);
        }

        return response()->json([
            'status_code' => $status,
            'message' => $message,
            'errors' => $errors
        ], $status, $headers);
    }

    /**
     * Return a new json response
     *
     * @param string $message
     * @param array $data
     * @param int $status
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseSuccess($message = '', $data = [], $status = 200, array $headers = [])
    {
        return response()->json([
            'status_code' => $status,
            'message' => $message,
            'data' => $data
        ], $status, $headers);
    }

    /**
     * Return json no messages
     *
     * @param array $data
     * @param int $status
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseSuccessNoMess($data = [], $status = 200, array $headers = [])
    {
        return $this->responseSuccess('', $data, $status, $headers);
    }

    /**
     * Return a new JSON response from the application.
     *
     * @param array $data Data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseCreate($data = [])
    {
        return $this->responseSuccess('', $data, Response::HTTP_CREATED);
    }
}
