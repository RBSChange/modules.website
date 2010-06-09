<?php
/**
 * @package modules.website.lib
 */
class website_ActionBase extends f_action_BaseAction
{

	/**
	 * Returns the website_MenuitemdocumentService to handle documents of type "modules_website/menuitemdocument".
	 *
	 * @return website_MenuitemdocumentService
	 */
	public function getMenuitemdocumentService()
	{
		return website_MenuitemdocumentService::getInstance();
	}

	/**
	 * Returns the website_PageversionService to handle documents of type "modules_website/pageversion".
	 *
	 * @return website_PageversionService
	 */
	public function getPageversionService()
	{
		return website_PageversionService::getInstance();
	}

	/**
	 * Returns the website_MenuitemService to handle documents of type "modules_website/menuitem".
	 *
	 * @return website_MenuitemService
	 */
	public function getMenuitemService()
	{
		return website_MenuitemService::getInstance();
	}

	/**
	 * Returns the website_MenuitemtextService to handle documents of type "modules_website/menuitemtext".
	 *
	 * @return website_MenuitemtextService
	 */
	public function getMenuitemtextService()
	{
		return website_MenuitemtextService::getInstance();
	}

	/**
	 * Returns the website_MenuitemfunctionService to handle documents of type "modules_website/menuitemfunction".
	 *
	 * @return website_MenuitemfunctionService
	 */
	public function getMenuitemfunctionService()
	{
		return website_MenuitemfunctionService::getInstance();
	}

	/**
	 * Returns the website_PagereferenceService to handle documents of type "modules_website/pagereference".
	 *
	 * @return website_PagereferenceService
	 */
	public function getPagereferenceService()
	{
		return website_PagereferenceService::getInstance();
	}

	/**
	 * Returns the website_MenufolderService to handle documents of type "modules_website/menufolder".
	 *
	 * @return website_MenufolderService
	 */
	public function getMenufolderService()
	{
		return website_MenufolderService::getInstance();
	}

	/**
	 * Returns the website_PreferencesService to handle documents of type "modules_website/preferences".
	 *
	 * @return website_PreferencesService
	 */
	public function getPreferencesService()
	{
		return website_PreferencesService::getInstance();
	}

	/**
	 * Returns the website_WebsiteService to handle documents of type "modules_website/website".
	 *
	 * @return website_WebsiteService
	 */
	public function getWebsiteService()
	{
		return website_WebsiteService::getInstance();
	}

	/**
	 * Returns the website_MenuService to handle documents of type "modules_website/menu".
	 *
	 * @return website_MenuService
	 */
	public function getMenuService()
	{
		return website_MenuService::getInstance();
	}

	/**
	 * Returns the website_PageService to handle documents of type "modules_website/page".
	 *
	 * @return website_PageService
	 */
	public function getPageService()
	{
		return website_PageService::getInstance();
	}

	/**
	 * Returns the website_TopicService to handle documents of type "modules_website/topic".
	 *
	 * @return website_TopicService
	 */
	public function getTopicService()
	{
		return website_TopicService::getInstance();
	}

	/**
	 * Returns the website_PageexternalService to handle documents of type "modules_website/pageexternal".
	 *
	 * @return website_PageexternalService
	 */
	public function getPageexternalService()
	{
		return website_PageexternalService::getInstance();
	}

	/**
	 * Returns the website_PagegroupService to handle documents of type "modules_website/pagegroup".
	 *
	 * @return website_PagegroupService
	 */
	public function getPagegroupService()
	{
		return website_PagegroupService::getInstance();
	}
}