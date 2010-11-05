<?php
class website_MenuItemPrintFunction
{
	/**
	 * @param website_MenuItem $menuItem
	 */
	static function execute($menuItem)
	{
			$currentWebsite = website_WebsiteModuleService::getInstance()->getCurrentWebsite();
		$page = TagService::getInstance()->getDocumentByContextualTag('contextual_website_website_print', $currentWebsite, false);
		if ($page !== null)
		{
			$menuItem->setUrl(LinkHelper::getDocumentUrl($page));
			$menuItem->setOnClick('return accessiblePrint(this)');
		}
		else 
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
			$currentWebsite = website_WebsiteModuleService::getInstance()->getCurrentWebsite();
		$page = TagService::getInstance()->getDocumentByContextualTag('contextual_website_website_favorite', $currentWebsite, false);
		if ($page !== null)
		{
			$menuItem->setUrl(LinkHelper::getDocumentUrl($page));
			$menuItem->setOnClick('return accessibleAddToFavorite(this)');
		}
		else
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