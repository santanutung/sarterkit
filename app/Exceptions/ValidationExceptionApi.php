<?php

namespace App\Exceptions;

use Exception;
use \Illuminate\Validation\Validator;

class ValidationExceptionApi extends Exception
{

	private $messages = array();

	public function __construct(Validator $validator = null)
	{
		if ($validator) {
			foreach ($validator->errors()
				->all() as $error) {
				$this->setMessage($error);
			}
		}
	}

	public function setMessage($message)
	{
		$this->messages[] = $message;
	}

	public function getMessages()
	{
		return $this->messages;
	}
}
