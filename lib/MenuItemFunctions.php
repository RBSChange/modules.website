<?php
class website_MenuItemPrintFunction
{
	/**
	 * @param website_MenuItem $menuItem
	 */
	static function execute($menuItem)
	{
		try
		{
			$currentWebsite = website_WebsiteModuleService::getInstance()->getCurrentWebsite();
			$menuItem->setUrl(LinkHelper::getUrl(TagService::getInstance()->getDocumentByContextualTag('contextual_website_website_print', $currentWebsite)));
			$menuItem->setOnClick('return accessiblePrint(this)');
		}
		catch (TagException $e)
		{
			$menuItem->setUrl('javascript:accessiblePrint()');
		}
	}
}

class website_MenuItemAddToFavoriteFunction
{
	/**
	 * @param website_MenuItem $menuItem
	 */
	static function execute($menuItem)
	{
		try
		{
			$currentWebsite = website_WebsiteModuleService::getInstance()->getCurrentWebsite();
			$menuItem->setUrl(LinkHelper::getUrl(TagService::getInstance()->getDocumentByContextualTag('contextual_website_website_favorite', $currentWebsite)));
			$menuItem->setOnClick('return accessibleAddToFavorite(this)');
		}
		catch (TagException $e)
		{
			$menuItem->setUrl('javascript: accessibleAddToFavorite()');
		}
	}
}

class website_MenuItemViewAsPDFFunction
{
	/**
	 * @param website_MenuItem $menuItem
	 */
	static function execute($menuItem)
	{
		$link = LinkHelper::getActionLink("generic", "ConvertPdf");
		$link->setQueryParameter("url", LinkHelper::getCurrentUrl());
		$menuItem->setUrl($link->getUrl());
	}
}