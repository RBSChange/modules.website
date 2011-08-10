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
	 * @var Zend_Service_ShortUrl_AbstractShortener 
	 */
	private $shortenerInstance;
	/**
	 * @return website_ShortenUrlService
	 */
	public static function getInstance()
	{
		if (is_null(self::$instance))
		{
			self::$instance = self::getServiceClassInstance(get_class());
			self::$instance->shortenerInstance = f_util_ClassUtils::newInstance(Framework::getConfiguration('modules/website/shortenerClassName'));
		}
		return self::$instance;
	}
	
	/**
	 * @param String $url
	 * @return String
	 */
	public function shortenUrl($url)
	{
		$this->shortenerInstance->setHttpClient(change_HttpClientService::getInstance()->getNewHttpClient());
		return $this->shortenerInstance->shorten($url);
	}
}