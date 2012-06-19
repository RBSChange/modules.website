<?php
/**
 * @package modules.website
 * @method website_ListTemplatesService getInstance()
 */
class website_ListStylesheetsService extends change_BaseService implements list_ListItemsService
{
	/**
	 * Stylesheets names reserved for internal used
	 * (won't be displayed in stylesheets list).
	 *
	 * @var array<string>
	 */
	private $_systemStylesheets = array('backoffice', 'print', 'bindings', 'frontoffice', 'richtext');

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