<?php

/**
 * SG Logistics client API
 *
 * @copyright Copyright (c) 2012-2013 Slevomat.cz, s.r.o.
 * @version 1.5
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
 * @property Address $address
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
		'weight' => null,
		'address' => null
	);

	/**
	 * Sets an attribute value.
	 *
	 * @param string $name Attribute name
	 * @param mixed $value Attribute value
	 *
	 * @throws \InvalidArgumentException If the file provided as the "picture" attribute value does not exist
	 * @throws \InvalidArgumentException If the "address" attribute was not an instance of \SgLogistics\Api\Entity\Address
	 */
	public function __set($name, $value)
	{
		if ('picture' === $name && !is_file($value)) {
			throw new \InvalidArgumentException(sprintf('File "%s" does not exist.', $value));
		}

		if ('address' === $name && !$value instanceof Address) {
			throw new \InvalidArgumentException(sprintf('The value of the "%s" attribute has to be an instance of \SgLogistics\Api\Entity\Address.', $name));
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
