<?php
class website_ListAvailablelangsforwebsiteService extends BaseService implements list_ListItemsService
{
	/**
	 * @var website_ListAvailablelangsforwebsiteService
	 */
	private static $instance;

	/**
	 * @return website_ListAvailablelangsforwebsiteService
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Returns an array of available stylesheets for the website module.
	 *
	 * @return array
	 */
	public function getItems()
	{
		$items = array();
		$website = null;
		if (isset($this->parameters['websiteId']))
		{
			$websiteId = $this->parameters['websiteId'];
			$website = DocumentHelper::getDocumentInstance($websiteId);
		}
		elseif (isset($this->parameters['folderId']))
		{
			$folder = DocumentHelper::getDocumentInstance($this->parameters['folderId']);
			$website = $folder->getDocumentService()->getParentOf($folder);
		}
		
		if ($website !== null)
		{
			foreach (explode(' ', AG_SUPPORTED_LANGUAGES) as $lang)
			{
				if ($website->isLangAvailable($lang))
				{
					$items[] = new list_Item(f_Locale::translateUI('&modules.uixul.bo.languages.' . ucfirst($lang) . ';'), $lang);
				}
			}
		}
		return $items;
	}
	
	/**
	 * @var Array
	 */
	private $parameters = array();
	
	/**
	 * @param Array $parameters
	 */
	public function setParameters($parameters)
	{
		$this->parameters = $parameters;
	}
}