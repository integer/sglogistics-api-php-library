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
 * Address.
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
class Address extends ApiEntity
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
