<?php

/**
 * SG Logistics client API
 *
 * @copyright Copyright (c) 2012-2013 Slevomat.cz, s.r.o.
 * @version 1.28
 * @apiVersion 1.2
 */

namespace SgLogistics\Api;

/**
 * API client helper.
 *
 * @category SgLogistics
 * @package  Api
 */
class Helper
{
	/**
	 * Returns a normalized product code.
	 *
	 * @param string $code Product code
	 * @return string
	 */
	public static function normalizeProductCode($code)
	{
		$code = strtoupper(static::toAscii($code));
		return preg_replace('~[^0-9A-Z|@]+~', '', $code);
	}

	/**
	 * Converts the given string to ASCII.
	 *
	 * https://github.com/nette/nette/blob/release-2.0.x/Nette/Utils/Strings.php#L170
	 *
	 * @param string $text The converted text (in UTF-8)
	 * @return string
	 */
	public static function toAscii($text)
	{
		$text = preg_replace('#[^\x09\x0A\x0D\x20-\x7E\xA0-\x{10FFFF}]#u', '', $text);
		$text = strtr($text, '`\'"^~', "\x01\x02\x03\x04\x05");
		if (ICONV_IMPL === 'glibc') {
			$text = @iconv('UTF-8', 'WINDOWS-1250//TRANSLIT', $text); // intentionally @
			$text = strtr($text, "\xa5\xa3\xbc\x8c\xa7\x8a\xaa\x8d\x8f\x8e\xaf\xb9\xb3\xbe\x9c\x9a\xba\x9d\x9f\x9e"
				. "\xbf\xc0\xc1\xc2\xc3\xc4\xc5\xc6\xc7\xc8\xc9\xca\xcb\xcc\xcd\xce\xcf\xd0\xd1\xd2\xd3"
				. "\xd4\xd5\xd6\xd7\xd8\xd9\xda\xdb\xdc\xdd\xde\xdf\xe0\xe1\xe2\xe3\xe4\xe5\xe6\xe7\xe8"
				. "\xe9\xea\xeb\xec\xed\xee\xef\xf0\xf1\xf2\xf3\xf4\xf5\xf6\xf8\xf9\xfa\xfb\xfc\xfd\xfe",
				"ALLSSSSTZZZallssstzzzRAAAALCCCEEEEIIDDNNOOOOxRUUUUYTsraaaalccceeeeiiddnnooooruuuuyt");
		} else {
			$text = @iconv('UTF-8', 'ASCII//TRANSLIT', $text); // intentionally @
		}
		$text = str_replace(array('`', "'", '"', '^', '~'), '', $text);
		return strtr($text, "\x01\x02\x03\x04\x05", '`\'"^~');
	}
}