<?php
/**
 * website_ShortenUrlService
 * @package modules.website.lib.services
 */
class website_ShortenUrlService extends BaseService
{
	/**
	 * Singleton
	 * @var website_ShortenUrlService
	 */
	private static $instance = null;

	/**
	 * @return website_ShortenUrlService
	 */
	public static function getInstance()
	{
		if (is_null(self::$instance))
		{
			self::$instance = self::getServiceClassInstance(get_class());
		}
		return self::$instance;
	}
	
	/**
	 * @param String $url
	 * @return String
	 */
	public function shortenUrl($url)
	{
		// TODO: handle other services to shorten the urls.
		$httpClient = HTTPClientService::getInstance()->getNewHTTPClient();
		$shortUrl = $httpClient->get('http://tinyurl.com/api-create.php?url=' . urlencode($url));
		if (!$shortUrl)
		{
			$shortUrl = $url;
		}
		return $shortUrl;
	}
}