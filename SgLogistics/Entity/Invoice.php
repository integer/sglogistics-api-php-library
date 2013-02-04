<?php

/**
 * SG Logistics client API
 *
 * @copyright Copyright (c) 2012-2013 Slevomat.cz, s.r.o.
 * @version 1.9
 * @apiVersion 1.0
 */

namespace SgLogistics\Api\Entity;

/**
 * Invoice.
 *
 * @category SgLogistics
 * @package  Api
 *
 * @property string $number
 * @property integer|string $issuedDate
 * @property integer|string $dueDate
 * @property integer|string $vatDate
 * @property integer $variableSymbol
 * @property array $rows
 */
class Invoice extends ApiEntity
{
	/**
	 * Entity data.
	 *
	 * @var array
	 */
	protected $data = array(
		'number' => null,
		'issuedDate' => null,
		'dueDate' => null,
		'vatDate' => null,
		'variableSymbol' => null,
		'rows' => array()
	);

	/**
	 * Adds a row to the invoice.
	 *
	 * @param InvoiceRow $item
	 * @return Invoice
	 *
	 * @throws \InvalidArgumentException If the given invoice row is already in the invoice.
	 */
	public function addRow(InvoiceRow $row)
	{
		if (in_array($row, $this->data['rows'], true)) {
			throw new \InvalidArgumentException('The given invoice row is already in the invoice.');
		}

		$this->data['rows'][] = $row;

		return $this;
	}
}
