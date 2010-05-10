<?php
class website_persistentdocument_website extends website_persistentdocument_websitebase
{
	/**
	 * @return string
	 */
	public function getFaviconMimeType()
	{
		$favicon = $this->getFavicon();
		return ($favicon !== null) ? $favicon->getMimetype() : 'image/x-icon';
	}
	
	/**
	 * @return string
	 */
	public function getFaviconUrl()
	{
		return $this->getUrl() . '/favicon.ico';
	}
}