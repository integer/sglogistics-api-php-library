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
 * Order shipping address.
 *
 * @category SgLogistics
 * @package  Api
 *
 * @property string $name
 * @property string $street
 * @property string $city
 * @property string $zipCode
 * @property string $countryCode
 * @property string $email
 * @property string $phone
 * @property string $companyId
 * @property string $companyVatId
 */
class OrderAddress extends ApiEntity
{
	/**
	 * Country code for the Czech Republic.
	 *
	 * @var string
	 */
	const COUNTRY_CZ = 'CZ';

	/**
	 * Country code for the Slovak Rebublic.
	 *
	 * @var string
	 */
	const COUNTRY_SK = 'SK';

	/**
	 * Entity data.
	 *
	 * @var array
	 */
	protected $data = array(
		'name' => null,
		'street' => null,
		'city' => null,
		'zipCode' => null,
		'countryCode' => null,
		'email' => null,
		'phone' => null,
		'companyId' => null,
		'companyVatId' => null
	);
}
