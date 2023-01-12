<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @param  array $result
     * @param  string $message
     * @return JsonResponse
     */
    public function sendResponse(array $result, string $message): JsonResponse
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];

        return response()->json($response, RESPONSE::HTTP_OK);
    }

    /**
     * success response method.
     *
     * @param AnonymousResourceCollection|JsonResource $result
     * @param  string $message
     * @return JsonResponse
     */
    public function sendJsonResponse(AnonymousResourceCollection|JsonResource $result, string $message): JsonResponse
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];

        return response()->json($response, RESPONSE::HTTP_OK);
    }



    /**
     * success response method.
     *
     * @param  ResourceCollection $result
     * @param  string $message
     * @return JsonResponse
     */
    public function sendJsonCollectionResponse(ResourceCollection $result, string $message): JsonResponse
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];

        return response()->json($response, RESPONSE::HTTP_OK);
    }

    /**
     * return error response.
     *
     * @param string $error
     * @param array $errorMessages
     * @param int $code
     * @return JsonResponse
     */
    public function sendError(
        string $error = 'error',
        array $errorMessages = [],
        int $code = RESPONSE::HTTP_NOT_FOUND
    ): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['errors'] = $errorMessages;
        } else {
            $response['errors'] = [];
        }

        return response()->json($response, $code);
    }
}
