<?php

namespace App\Validators;

use Respect\Validation\Exceptions\NestedValidationException;

class Validator 
{
	protected $errors = [];

	public function validate($request , array $rules)
	{
		foreach ($rules as $name => $rule) {
			try {
				$rule->setName(ucfirst($name))->assert($request->getParam($name));

			} catch (NestedValidationException $e) {

				$this->errors[$name] = $e->getMessages();
			}
		}

		$_SESSION['errors'] =  $this->getErrors();

		return $this;
	}

	public function isFalid()
	{
		return !empty($this->errors);
	}

	public function getErrors()
	{
		return $this->errors;
	}
}