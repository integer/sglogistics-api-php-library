<?php

/**
 * SG Logistics client API
 *
 * @copyright Copyright (c) 2012 Slevomat.cz, s.r.o.
 * @version 0.9
 * @apiVersion 0.9
 */

namespace SgLogistics\Api\Entity;

/**
 * Base class for API entities.
 *
 * @category SgLogistics
 * @package  Api
 */
abstract class ApiEntity
{
	/**
	 * Entity data.
	 *
	 * @var array
	 */
	protected $data = array();

	/**
	 * Constructor.
	 *
	 * @param array $data The entity data to be imported.
	 */
	public function __construct(array $data = array())
	{
		$this->import($data);
	}

	/**
	 * Import the given data into the entity.
	 *
	 * @param array $data A data to be imported.
	 *
	 * @return ApiEntity Provides a fluent interface.
	 */
	public function import(array $data)
	{
		foreach ($data as $key => $value) {
			$this->__set($key, $value);
		}

		return $this;
	}

	/**
	 * Exports the entity into an array.
	 *
	 * @return array
	 */
	public function export()
	{
		return array_map(function($value) {
			return $value instanceof ApiEntity ? $value->export() : $value;
		}, $this->data);
	}

	/**
	 * Sets an attribute value.
	 *
	 * @param string $name Attribute name
	 * @param mixed $value Attribute value
	 */
	public function __set($name, $value)
	{
		if (!$this->__isset($name)) {
			throw new \InvalidArgumentException(sprintf('There is no attribute "%s".', $name));
		}

		$this->data[$name] = $value;
	}

	/**
	 * Returns an attribute value.
	 *
	 * @param string $name Attribute name
	 */
	public function __get($name)
	{
		if (!$this->__isset($name)) {
			throw new \InvalidArgumentException(sprintf('There is no attribute "%s".', $name));
		}

		return $this->data[$name];
	}

	/**
	 * Returns if there is an attribute of the given name.
	 *
	 * @param string $name Attribute name
	 * @return boolean
	 */
	public function __isset($name)
	{
		return array_key_exists($name, $this->data);
	}
}