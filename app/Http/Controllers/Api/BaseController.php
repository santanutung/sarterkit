<?php

namespace App\Http\Controllers\Api;

use App\General\ApiResponse;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{


	/**
	 * @param boolean $status
	 * @param array $data
	 * @param null $errorMessage
	 * @throws \Exception
	 */

	protected function response($status, array $data = null, $errorMessage = null)
	{
		$response = new ApiResponse();

		if (!is_null($data)) {
			$response->setDataArray($data);
		}

		if (!is_null($errorMessage)) {
			$response->setError($errorMessage);
		}

		$response->setStatus($status);
		return $response->response();
	}



	public function success(mixed $data, string $message = "", int $statusCode = 200): JsonResponse
	{
		return response()->json([
			'status' => 1,
			'message' => $message,
			'data' => $data,
			"errors" => [],
		], $statusCode);

		// return response()->json([
		// 	'data' => $data,
		// 	'success' => true,
		// 	'message' => $message,
		// ], $statusCode);


	}
}
