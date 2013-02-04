<?php

/**
 * SG Logistics client API
 *
 * @copyright Copyright (c) 2012-2013 Slevomat.cz, s.r.o.
 * @version 1.9
 * @apiVersion 1.0
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
	 * Brand produktu.
	 *
	 * @var string
	 */
	protected $productBrand;

	/**
	 * Kód produktu.
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
	 * Vrátí brand požadovaného produktu.
	 *
	 * @return string
	 */
	public function getProductBrand()
	{
		return $this->productBrand;
	}

	/**
	 * Vrátí kód požadovaného produktu.
	 *
	 * @return string
	 */
	public function getProductCode()
	{
		return $this->productCode;
	}
}
