<?php
class website_MenuItemPrintFunction
{
	/**
	 * @param website_MenuEntry $entry
	 */
	static function execute($entry)
	{
		$currentWebsite = website_WebsiteService::getInstance()->getCurrentWebsite();
		$page = TagService::getInstance()->getDocumentByContextualTag('contextual_website_website_print', $currentWebsite, false);
		if ($page !== null)
		{
			$entry->setUrl(LinkHelper::getDocumentUrl($page));
			$entry->setOnClick('return accessiblePrint(this)');
		}
		else
		{
			$entry->setUrl('javascript:accessiblePrint()');
		}
	}
}

class website_MenuItemAddToFavoriteFunction
{
	/**
	 * @param website_MenuEntry $entry
	 */
	static function execute($entry)
	{
		$currentWebsite = website_WebsiteService::getInstance()->getCurrentWebsite();
		$page = TagService::getInstance()->getDocumentByContextualTag('contextual_website_website_favorite', $currentWebsite, false);
		if ($page !== null)
		{
			$entry->setUrl(LinkHelper::getDocumentUrl($page));
			$entry->setOnClick('return accessibleAddToFavorite(this)');
		}
		else
		{
			$entry->setUrl('javascript: accessibleAddToFavorite()');
		}
	}
}

class website_MenuItemViewAsPDFFunction
{
	/**
	 * @param website_MenuEntry $entry
	 */
	static function execute($entry)
	{
		$link = LinkHelper::getActionLink('generic', 'ConvertPdf');
		$link->setQueryParameter('url', LinkHelper::getCurrentUrl());
		$entry->setUrl($link->getUrl());
	}
}