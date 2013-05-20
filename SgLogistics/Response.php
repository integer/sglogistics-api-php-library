<?php

/**
 * SG Logistics client API
 *
 * @copyright Copyright (c) 2012-2013 Slevomat.cz, s.r.o.
 * @version 1.13
 * @apiVersion 1.2
 */

namespace SgLogistics\Api;

/**
 * API method call response.
 *
 * @category SgLogistics
 * @package  Api
 */
class Response
{
	/**
	 * Everything is ok.
	 *
	 * @var int
	 */
	const STATUS_SUCCESS = 200;

	/**
	 * An exception has occured during the method execution.
	 *
	 * @var int
	 */
	const STATUS_EXCEPTION = 400;

	/**
	 * Something is seriously wrong.
	 *
	 * @var int
	 */
	const STATUS_ERROR = 500;

	/**
	 * The response status code.
	 *
	 * @var int
	 */
	private $status;

	/**
	 * A message attached to the response.
	 *
	 * @var string
	 */
	private $message;

	/**
	 * The definition of an exception which has been thrown during the method execution.
	 *
	 * @var array
	 */
	private $exception;

	/**
	 * The result returned by the called method.
	 *
	 * @var mixed
	 */
	private $result;

	/**
	 * Constructor.
	 *
	 * @param mixed $result The result returned by the called method.
	 * @param int $status The response status code.
	 * @param string $message A message attached to the response.
	 * @param array $exception The definition of an exception which has been thrown during the method execution.
	 *
	 * @throws \InvalidArgumentException If the response status code indicates an exception but
	 *                                   no exception definiton has been provided.
	 */
	public function __construct($result, $status = self::STATUS_SUCCESS, $message = '', array $exception = array())
	{
		$this->result = $result;
		$this->status = (int) $status;
		$this->message = (string) $message;
		$this->exception = $exception;

		if (self::STATUS_EXCEPTION === $this->status && empty($exception)) {
			throw new \InvalidArgumentException('No exception definiton provided.');
		}
	}

	/**
	 * Get the response status code.
	 *
	 * @return int The response status code.
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * Get a message attached to the response.
	 *
	 * @return string A message attached to the response.
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * Get the exception definition from the response.
	 *
	 * @return array
	 */
	public function getExceptionDefinition()
	{
		return $this->exception;
	}

	/**
	 * Get an exception which has been thrown during the method execution.
	 *
	 * @return \Exception|null An exception which has been thrown during the method execution.
	 */
	public function getException()
	{
		if (self::STATUS_SUCCESS === $this->result) {
			return null;
		}

		if (empty($this->exception)) {
			return new \SgLogistics\Api\Exception\ServerError('There was an error performing your request but no details are available.');
		}

		return \SgLogistics\Api\Exception\Response::createFromResponse($this);
	}

	/**
	 * Get the result returned by the called method.
	 *
	 * @return mixed The result returned by the called method.
	 */
	public function getResult()
	{
		return $this->result;
	}
}
