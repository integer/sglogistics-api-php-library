<?php

/**
 * SG Logistics client API
 *
 * @copyright Copyright (c) 2012-2013 Slevomat.cz, s.r.o.
 * @version 1.13.1
 * @apiVersion 1.2
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
 * @property string $type
 * @property float $sellingPrice
 * @property float $buyingPrice
 * @property boolean $oversize
 * @property array $sizes
 * @property string $intrastat
 */
class Product extends ApiEntity
{
	/**
	 * Product type - services.
	 *
	 * @var string
	 */
	const TYPE_SERVICES = 'services';

	/**
	 * Product type - chemist's goods.
	 *
	 * @var string
	 */
	const TYPE_GOODS_CHEMIST = 'goods_chemist';

	/**
	 * Product type - home and decor.
	 *
	 * @var string
	 */
	const TYPE_GOODS_HOME = 'goods_home';

	/**
	 * Product type - fashion goods.
	 *
	 * @var string
	 */
	const TYPE_GOODS_FASHION = 'goods_fashion';

	/**
	 * Product type - other goods.
	 *
	 * @var string
	 */
	const TYPE_GOODS_OTHER = 'goods_other';

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
		'address' => null,
		'type' => null,
		'sellingPrice' => null,
		'buyingPrice' => null,
		'oversize' => null,
		'sizes' => null,
		'intrastat' => null,
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

		if ('type' === $name && !in_array($value, static::getTypes())) {
			throw new \InvalidArgumentException(sprintf('The value of the "%s" attribute has to be one of %s.', $name, implode(', ', static::getTypes())));
		}

		if ('intrastat' === $name && $value !== null && !in_array($value, static::getIntrastatTypes())) {
			throw new \InvalidArgumentException(sprintf('The value of the "%s" attribute has to be one of %s.', $name, implode(', ', static::getIntrastatTypes())));
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

	/**
	 * Returns possible product types.
	 *
	 * @return array
	 */
	public static function getTypes()
	{
		static $types;
		if (!isset($types)) {
			$reflection = new \ReflectionClass(get_called_class());
			$constants = $reflection->getConstants();

			foreach ($constants as $name => $value) {
				if (0 !== strpos($name, 'TYPE_')) {
					unset($constants[$name]);
				}
			}

			$types = array_values($constants);
		}

		return $types;
	}

	/**
	 * Returns possible intrastat types.
	 *
	 * @return array
	 */
	public static function getIntrastatTypes()
	{
		static $types;
		if (!isset($types)) {
			$reflection = new \ReflectionClass(get_called_class());
			$constants = $reflection->getConstants();

			foreach ($constants as $name => $value) {
				if (0 !== strpos($name, 'INTRASTAT_')) {
					unset($constants[$name]);
				}
			}

			$types = array_values($constants);
		}

		return $types;
	}
}
