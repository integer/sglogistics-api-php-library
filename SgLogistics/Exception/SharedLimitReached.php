<?php

/**
 * SG Logistics client API
 *
 * @copyright Copyright (c) 2012-2013 Slevomat.cz, s.r.o.
 * @version 1.17
 * @apiVersion 1.2
 */

namespace SgLogistics\Api\Exception;

/**
 * Thrown when there are no available pieces of a product which could be reserved.
 *
 * @category   SgLogistics
 * @package    Api
 * @subpackage Exception
 */
class SharedLimitReached extends Response
{
	/**
	 * Product.
	 *
	 * @var string
	 */
	private $product;

	/**
	 * The number of remaining pieces of the product.
	 *
	 * @var int
	 */
	private $limit;

	/**
	 * Constructor.
	 *
	 * @param string $message Message
	 * @param string $product Product
	 * @param int $limit The number of remaining pieces of the product.
	 */
	public function __construct($message, $product, $limit)
	{
		parent::__construct((string) $message);

		$this->product = (string) $product;
		$this->limit = (int) $limit;
	}

	/**
	 * Get the product.
	 *
	 * @return string Product
	 */
	public function getProduct()
	{
		return $this->product;
	}

	/**
	 * Get the number of remaining pieces of the product.
	 *
	 * @return int The number of remaining pieces of the product.
	 */
	public function getLimit()
	{
		return $this->limit;
	}
}
