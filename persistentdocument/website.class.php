<?php
class website_persistentdocument_website extends website_persistentdocument_websitebase
{
	
	/**
	 * @var string
	 */
	private $structureinit;
	
	/**
	 * @return string
	 */
	public function getStructureinit()
	{
		return $this->structureinit;
	}
	
	/**
	 * @param string $structureinit
	 */
	public function setStructureinit($structureinit)
	{
		$this->structureinit = $structureinit;
	}
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