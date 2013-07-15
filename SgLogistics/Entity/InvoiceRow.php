<?php

/**
 * SG Logistics client API
 *
 * @copyright Copyright (c) 2012-2013 Slevomat.cz, s.r.o.
 * @version 1.16
 * @apiVersion 1.2
 */

namespace SgLogistics\Api\Entity;

/**
 * Invoice row.
 *
 * @category SgLogistics
 * @package  Api
 *
 * @property string $brand
 * @property string $code
 * @property integer $campaign
 * @property string $description
 * @property float $amount
 * @property float $vatRate
 */
class InvoiceRow extends ApiEntity
{
	/**
	 * Entity data.
	 *
	 * @var array
	 */
	protected $data = array(
		'brand' => null,
		'code' => null,
		'campaign' => null,
		'description' => null,
		'amount' => null,
		'vatRate' => null
	);
}
