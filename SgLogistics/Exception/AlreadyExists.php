<?php

/**
 * SG Logistics client API
 *
 * @copyright Copyright (c) 2012-2013 Slevomat.cz, s.r.o.
 * @version 1.13.1
 * @apiVersion 1.2
 */

namespace SgLogistics\Api\Exception;

/**
 * Exception thrown when the created entity already exists.
 *
 * @category SgLogistics
 * @package  Api
 */
class AlreadyExists extends InvalidValue
{
	/**
	 * Entity name.
	 *
	 * @var string
	 */
	protected $entityName;

	/**
	 * Creates the exception.
	 *
	 * @param string $entityName Entity name
	 * @param string $message Exception message
	 * @param string $parameterName Parameter name
	 * @param mixed $value Parameter value
	 */
	public function __construct($entityName, $message, $parameterName, $value)
	{
		parent::__construct(sprintf('Could not create a new %s with %s "%s". Such %s already exists', $entityName, $parameterName, $value, $entityName), $parameterName, $value);

		$this->entityName = $entityName;
	}

	/**
	 * Return the entity name.
	 *
	 * @return string
	 */
	public function getEntityName()
	{
		return $this->entityName;
	}
}
