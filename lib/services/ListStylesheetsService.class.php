<?php
class website_ListStylesheetsService extends BaseService implements list_ListItemsService
{
	/**
	 * @var website_ListStylesheetsService
	 */
	private static $instance;
	
	/**
	 * Stylesheets names reserved for internal used
	 * (won't be displayed in stylesheets list).
	 *
	 * @var array<string>
	 */
	private $_systemStylesheets = array('backoffice', 'print', 'bindings', 'frontoffice', 'richtext');
	
	/**
	 * @return website_ListStylesheetsService
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
		foreach (website_WebsiteModuleService::getInstance()->getWebsiteAndTopicStylesheets() as $fileName => $label)
		{
			$items[] = new list_Item(f_Locale::translateUI($label), $fileName);
		}
		return $items;
	}
}