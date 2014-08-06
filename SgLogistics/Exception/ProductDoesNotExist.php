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
 * Exception thrown when the requested product does not exist.
 *
 * @category SgLogistics
 * @package  Api
 */
class ProductDoesNotExist extends InvalidValue
{
	/**
	 * Product brand.
	 *
	 * @var string
	 */
	protected $productBrand;

	/**
	 * Product code.
	 *
	 * @var string
	 */
	protected $productCode;

	/**
	 * Creates the exception.
	 *
	 * @param string $productBrand Given product brand
	 * @param string $code Given product code
	 */
	public function __construct($productBrand, $productCode)
	{
		parent::__construct('The given product does not exist.', 'brand×code',  $productBrand . ' ' . $productCode);

		$this->productBrand = $productBrand;
		$this->productCode = $productCode;
	}

	/**
	 * Returns the product brand.
	 *
	 * @return string
	 */
	public function getProductBrand()
	{
		return $this->productBrand;
	}

	/**
	 * Returns the product code.
	 *
	 * @return string
	 */
	public function getProductCode()
	{
		return $this->productCode;
	}
}
