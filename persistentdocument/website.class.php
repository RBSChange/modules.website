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
	 * @var string
	 */
	private $template;
	
	/**
	 * @return string
	 */
	public function getTemplate()
	{
		return $this->template;
	}
	
	/**
	 * @param string $template
	 */
	public function setTemplate($template)
	{
		$this->template = $template;
	}
	
	/**
	 * @var string
	 */
	private $templateHome;
	
	/**
	 * @return string
	 */
	public function getTemplateHome()
	{
		return $this->templateHome;
	}
	
	/**
	 * @param string $templateHome
	 */
	public function setTemplateHome($templateHome)
	{
		$this->templateHome = $templateHome;
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
	
	
	//MARKER EDITION
	
	/**
	 * @return string
	 */
	public function getMarkerGridJSON()
	{
		$array = array();
		$markers = website_MarkerService::getInstance()->getAllByWebsite($this);
		foreach ($markers as $marker) 
		{
			$array[] = $marker->getDocumentService()->getMarkerGridInfo($marker);
		}
		return JsonService::getInstance()->encode($array);
	}
	
	private $newMarkerType;
	private $newMarkerAccount;
	
	
	public function getNewMarkerType()
	{
		return $this->newMarkerType;
	}
	
	public function setNewMarkerType($newMarkerType)
	{
		$this->newMarkerType = $newMarkerType;
		if ($newMarkerType)
		{
			$this->setModificationdate(null);
		}
	}	
	
	public function getNewMarkerAccount()
	{
		return $this->newMarkerAccount;
	}
	
	public function setNewMarkerAccount($newMarkerAccount)
	{
		$this->newMarkerAccount = $newMarkerAccount;
		if ($newMarkerAccount)
		{
			$this->setModificationdate(null);
		}
	}
}