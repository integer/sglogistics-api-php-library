<?php

/**
 * SG Logistics client API
 *
 * @copyright Copyright (c) 2012 Slevomat.cz, s.r.o.
 * @version 1.0
 * @apiVersion 1.0
 */

namespace SgLogistics\Api\Entity;

/**
 * Product.
 *
 * @category SgLogistics
 * @package  Api
 *
 * @property string $brand
 * @property string $code
 * @property string $name
 * @property string $picture
 * @property integer $weight
 */
class Product extends ApiEntity
{
	/**
	 * Entity data.
	 *
	 * @var array
	 */
	protected $data = array(
		'brand' => null,
		'code' => null,
		'name' => null,
		'picture' => null,
		'weight' => null
	);

	/**
	 * Sets an attribute value.
	 *
	 * @param string $name Attribute name
	 * @param mixed $value Attribute value
	 *
	 * @throws \InvalidArgumentException If the file provided as the "picture" attribute value does not exist
	 */
	public function __set($name, $value)
	{
		if ('picture' === $name && !is_file($value)) {
			throw new \InvalidArgumentException(sprintf('File "%s" does not exist.', $value));
		}

		parent::__set($name, $value);
	}

	/**
	 * Exports the entity into an array.
	 *
	 * @return array
	 */
	public function export()
	{
		$export = parent::export();

		if (!empty($export['picture'])) {
			$export['picture'] = '@' . $export['picture'];
		}

		return $export;
	}
}
