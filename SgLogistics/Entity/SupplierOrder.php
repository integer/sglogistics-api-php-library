<?php

/**
 * SG Logistics client API
 *
 * @copyright Copyright (c) 2012-2013 Slevomat.cz, s.r.o.
 * @version 1.18
 * @apiVersion 1.2
 */

namespace SgLogistics\Api\Entity;

/**
 * Supplier order.
 *
 * @category SgLogistics
 * @package  Api
 *
 * @property integer|string $created
 * @property Address $address
 * @property string $productsFile
 * @property string $description
 * @property string $currency
 * @property boolean $consignment
 */
class SupplierOrder extends ApiEntity
{
	/**
	 * Entity data.
	 *
	 * @var array
	 */
	protected $data = array(
		'created' => null,
		'address' => null,
		'productsFile' => null,
		'description' => null,
		'currency' => null,
		'consignment' => null,
	);

	/**
	 * Sets an attribute value.
	 *
	 * @param string $name Attribute name
	 * @param mixed $value Attribute value
	 *
	 * @throws \InvalidArgumentException If the file provided as the "itemsFile" attribute value does not exist
	 * @throws \InvalidArgumentException If the "supplierAddress" attribute was not an instance of \SgLogistics\Api\Entity\Address
	 */
	public function __set($name, $value)
	{
		if ('productsFile' === $name && !is_file($value)) {
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

		if (!empty($export['productsFile'])) {
			$export['productsFile'] = '@' . $export['productsFile'];
		}

		return $export;
	}
}
