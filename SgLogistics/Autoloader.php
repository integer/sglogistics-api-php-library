<?php

/**
 * SG Logistics client API
 *
 * @copyright Copyright (c) 2012-2013 Slevomat.cz, s.r.o.
 * @version 1.20
 * @apiVersion 1.2
 */

namespace SgLogistics\Api;

/**
 * SG Logistics API client autoloader.
 *
 * @category SgLogistics
 * @package  Api
 */
class Autoloader
{
	/**
	 * Registers the autoloader.
	 */
	public function register()
	{
		spl_autoload_register(function($className) {
			$className = ltrim($className, '\\');

			if (0 === strpos($className, __NAMESPACE__)) {
				require_once __DIR__ . '/' . str_replace('\\', '/', substr($className, strlen(__NAMESPACE__) + 1)) . '.php';
				return class_exists($className, false);
			}
		});
	}
}
