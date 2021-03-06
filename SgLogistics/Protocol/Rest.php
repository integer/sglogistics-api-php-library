<?php

/**
 * SG Logistics client API
 *
 * @copyright Copyright (c) 2012-2013 Slevomat.cz, s.r.o.
 * @version 1.28
 * @apiVersion 1.2
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
	 * User agent header value.
	 *
	 * @var string
	 */
	private $userAgent;

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
	 * Sets the user agent header value for HTTP requests.
	 *
	 * @param string $userAgent Header value
	 * @return \SgLogistics\Api\Protocol\ProtocolInterface
	 */
	public function setUserAgent($userAgent)
	{
		$this->userAgent = (string) $userAgent;

		return $this;
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

		$postFiles = array();
		foreach ($post as $fieldName => $fieldValue) {
			if (!is_array($fieldValue) && isset($fieldValue{0}) && '@' === $fieldValue{0}) {
				$postFiles[$fieldName] = $this->postFile($fieldValue);
				unset($post[$fieldName]);
			}
		}

		$post = $postFiles + array('_content' => json_encode($post));

		$c = curl_init($url);
		curl_setopt_array($c, array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $post,
			CURLOPT_USERAGENT => sprintf('%s php_curl', $this->userAgent),
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

	/**
	 * Prepare the given file to be sent via HTTP POST.
	 *
	 * @param string $file The file to be sent.
	 *
	 * @return \CURLFile|string
	 */
	protected function postFile($file)
	{
		static $classExists = null;

		if (null === $classExists) {
			$classExists = class_exists('CURLFile');
		}

		$file = trim((string) $file);

		return $classExists ? new \CURLFile(ltrim($file, '@')) : $file;
	}
}
