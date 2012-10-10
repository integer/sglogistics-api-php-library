<?php

/**
 * SG Logistics client API
 *
 * @copyright Copyright (c) 2012 Slevomat.cz, s.r.o.
 * @version 0.9
 * @apiVersion 0.9
 */

namespace SgLogistics\Api\Protocol;

/**
 * REST protocol.
 *
 * @category   SgLogistics
 * @package    Api
 * @subpackage Protocol
 */
class Rest implements ProtocolInterface
{
	/**
	 * Route name.
	 *
	 * @var string
	 */
	private $route = '/api.rest/%s?%s';

	/**
	 * Server URL.
	 *
	 * @var string
	 */
	private $hostUrl;

	/**
	 * Constructor.
	 *
	 * @param string $hostUrl Server URL.
	 *
	 * @throws \Exception If the cURL PHP extension is not loaded
	 */
	public function __construct($hostUrl)
	{
		if (!extension_loaded('curl')) {
			throw new \Exception('The cURL extension is required.');
		}

		$this->hostUrl = (string) $hostUrl;
	}

	/**
	 * Converts an array to the CURLOPT_POSTFIELDS form.
	 *
	 * @param string $name Parameter name
	 * @param array $values Parameter value
	 * @return array
	 */
	private function convertArray($name, array $values)
	{
		$result = array();
		foreach ($values as $index => $value) {
			$fieldName = sprintf('%s[%s]', $name, $index);

			if (is_array($value)) {
				$result = array_merge($result, $this->convertArray($fieldName, $value));
			} else {
				$result[$fieldName] = $value;
			}
		}

		return $result;
	}

	/**
	 * Sends a request and returns the response.
	 *
	 * @param string $method Method name
	 * @param array $arguments Array of arguments
	 *
	 * @return \SgLogistics\Api\Response Method call result.
	 */
	public function request($method, array $arguments = array())
	{
		$post = array_filter($arguments, function($i) { return !is_scalar($i) || strlen($i) > 255 || (isset($i[0]) && '@' === $i{0}); });
		$arguments = array_diff_key($arguments, $post);
		$url = $this->hostUrl . sprintf($this->route, $method, http_build_query($arguments));

		foreach ($post as $fieldName => $fieldValue) {
			if (is_array($fieldValue)) {
				unset($post[$fieldName]);
				foreach ($this->convertArray($fieldName, $fieldValue) as $name => $value) {
					$post[$name] = $value;
				}
			}
		}

		$c = curl_init($url);
		curl_setopt_array($c, array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $post
		));

		$content = curl_exec($c);

		if (false === $content) {
			$message = sprintf('Could not execute the cURL request to URL "%s" (%d: %s).', $url, curl_errno($c), curl_error($c));
			curl_close($c);
			throw new \Exception($message);
		}

		curl_close($c);

		$response = @json_decode($content, true);

		$result = isset($response['result']) ? $response['result'] : null;
		$status = isset($response['status']) ? $response['status'] : \SgLogistics\Api\Response::STATUS_ERROR;
		$message = isset($response['message']) ? $response['message'] : '';
		$exception = isset($response['exception']) ? $response['exception'] : array();

		return new \SgLogistics\Api\Response($result, $status, $message, $exception);
	}
}
