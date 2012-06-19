<?php
/**
 * @package modules.website
 * @method website_ShortenUrlService getInstance()
 */
class website_ShortenUrlService extends change_BaseService
{
	/**
	 * @var Zend_Service_ShortUrl_AbstractShortener 
	 */
	private $shortenerInstance;
	
	/**
	 * @param string $url
	 * @return string
	 */
	public function shortenUrl($url)
	{
		if ($this->shortenerInstance == null)
		{
			$this->shortenerInstance = f_util_ClassUtils::newInstance(Framework::getConfiguration('modules/website/shortenerClassName'));
		}
		$this->shortenerInstance->setHttpClient(change_HttpClientService::getInstance()->getNewHttpClient());
		return $this->shortenerInstance->shorten($url);
	}
}