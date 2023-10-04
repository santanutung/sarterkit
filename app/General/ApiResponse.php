<?php

namespace App\General;

use Exception;
use \App\Exceptions\ValidationExceptionApi;
use \Illuminate\Http\Response;
use \Symfony\Component\HttpKernel\Exception\HttpException;
use Response as ResponseFacade;

class ApiResponse {

	public $success = true;
	private $messages = array();
	private $errors = array();
	private $data = array();
	private $httpStatus = 200;

	function __construct(array $param = []) {
		if (count($param) > 0) {
			foreach ($param as $key => $value) {
				$this->setData($key, $value);
			}
		}
	}

	public function setError($error) {
		if ($error instanceof ValidationExceptionApi) {
			foreach ($error->getMessages() as $message) {
				$this->errors[] = $message;
			}
		}
		else if (is_array($error)) {
			foreach ($error as $message) {
				$this->errors[] = $message;
			}
		}
		else if ($error instanceof Exception) {
			// used by the exception handler for uncaught exceptions
			$this->success = false;
			$this->setHttpStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
			if (env('APP_DEBUG') === true) {
				$this->setData('exception', [
					'type' => get_class($error),
					'message' => $error->getMessage(),
					'file' => $error->getFile(),
					'line' => $error->getLine(),
				]);
			}
		}
		else if (is_string($error)) {
			$this->errors[] = $error;
		}
		else {
			$this->errors[] = $error->getMessage();
		}

		if ($error instanceof HttpException) {
			$this->setHttpStatus($error->getStatusCode());
		}
		$this->success = false;
	}

	public function setMessage($message) {
		$this->messages[] = $message;
	}

	public function setStatus($status) {
		$this->success = $status;
	}

	public function setHttpStatus($status) {
		$this->httpStatus = $status;
	}

	public function setData($name, $data) {
		$this->data[$name] = $data;
	}

	public function getData($name) {
		return array_key_exists($name, $this->data) ? $this->data[$name] : null;
	}

	public function setDataArray($dataArray) {
		if (is_array($dataArray)) {
			foreach ($dataArray as $key => $value) {
				$this->setData($key, $value);
			}
		}
		else {
			throw new Exception('Tried to set data array with a non-array value');
		}
	}

	public function getDataArray() {
		return $this->data;
	}

	public function deleteData($name) {
		if (isset($this->data[$name])) {
			unset($this->data[$name]);
		}
	}

	public function toArray() {
		$arr = array(
			'status' => $this->success,
			'messages' => $this->messages,
			'errors' => $this->errors,
		);
		foreach ($this->data as $k => $data) {
			$arr[$k] = $data;
		}
		return $arr;
	}

	public function response() {
		 
		return ResponseFacade::json($this->toArray(), $this->httpStatus);
	}

	public static function success() {
		return ResponseFacade::json(array(
			'status' => 1,
			'errors' => array(),
			'messages' => array(),
		), 200);
	}

	public static function error($error, $status = 200) {
		return ResponseFacade::json(array(
			'status' => 0,
			'errors' => array($error),
			'messages' => array(),
		), $status);
	}

}
