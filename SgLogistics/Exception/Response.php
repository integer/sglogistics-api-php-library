<?php

/**
 * SG Logistics client API
 *
 * @copyright Copyright (c) 2012-2013 Slevomat.cz, s.r.o.
 * @version 1.27
 * @apiVersion 1.2
 */

namespace SgLogistics\Api\Exception;

/**
 * Exception thrown when there is an error in the server response.
 *
 * @category SgLogistics
 * @package  Api
 */
class Response extends \RuntimeException
{
	/**
	 * API response that created this exception.
	 *
	 * @var \SgLogistics\Api\Response
	 */
	private $response;

	/**
	 * Creates the exception.
	 *
	 * @param string $message Exception message
	 */
	public function __construct($message)
	{
		parent::__construct($message);
	}

	/**
	 * Returns the API response that created this exception.
	 *
	 * @return \SgLogistics\Api\Response
	 */
	public function getResponse()
	{
		return $this->response;
	}

	/**
	 * Returns a definition of the exception parameters.
	 *
	 * @return array
	 */
	final public function getDefinition()
	{
		$reflection = new \ReflectionClass($this);

		$definition = array();
		foreach ($reflection->getConstructor()->getParameters() as $parameter) {
			$getter = 'get' . ucfirst($parameter->getName());
			$definition[$parameter->getName()] = $this->{$getter}();
		}
		return $definition;
	}

	/**
	 * Creates an exception from the response.
	 *
	 * @param \SgLogistics\Api\Response $response API response
	 * @return Response
	 */
	public static function createFromResponse(\SgLogistics\Api\Response $response)
	{
		$definition = $response->getExceptionDefinition();
		$className = $definition['class'];
		unset($definition['class'], $definition['type']);

		if (!class_exists($className)) {
			$exception = new Response(sprintf('There was an exception of type "%s" in the API response but such exception does not exist.', $className));
			$exception->response = $response;
			throw $exception;
		}

		$r = new \ReflectionClass($className);
		$exception = $r->newInstanceArgs(array_values(isset($definition['parameters']) ?  $definition['parameters'] : $definition));
		$exception->response = $response;

		return $exception;
	}
}
