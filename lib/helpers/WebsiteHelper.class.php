<?php
/**
 * @date Mon Feb 12 11:59:01 CET 2007
 * @author INTbonjF
 */
abstract class WebsiteHelper
{
	private static $folderDocuments = array('modules_website/topic', 'modules_website/systemtopic', 'modules_website/website');

	public static function isVisible($item)
	{
		return self::isVisibleInMenu($item);
	}
		
	/**
	 * @param f_persistentdocument_PersistentDocument $item
	 * @return boolean
	 */
	public static function isVisibleInMenu($item)
	{
		if ($item->isPublished())
		{
			if ($item instanceof website_persistentdocument_menuitem)
			{
				$itemVisibility = $item->getNavigationVisibility();
			}
			else if ($item instanceof website_PublishableElement)
			{
				$itemVisibility = $item->getNavigationVisibility();
			}
			else
			{
				$itemVisibility = WebsiteConstants::VISIBILITY_HIDDEN;
			}
			
			return (WebsiteConstants::VISIBILITY_VISIBLE == $itemVisibility || 
					WebsiteConstants::VISIBILITY_HIDDEN_IN_SITEMAP_ONLY == $itemVisibility);
		}
		return false;
	}

	/**
	 * @param f_persistentdocument_PersistentDocument $item
	 * @return boolean
	 */
	public static function isVisibleInSitemap($item)
	{
		if ($item instanceof website_PublishableElement && $item->isPublished())
		{
			$itemVisibility = $item->getNavigationVisibility();
			return WebsiteConstants::VISIBILITY_VISIBLE == $itemVisibility || 
					WebsiteConstants::VISIBILITY_HIDDEN_IN_MENU_ONLY == $itemVisibility;
		}
		return false;		
	}



	public static function isHidden($item)
	{
		if ($item instanceof website_persistentdocument_menuitem)
		{
			return false;
		}
		
		$v = self::getVisibility($item);
		return $v == WebsiteConstants::VISIBILITY_HIDDEN;
	}


	/**
	 * @param object $entry
	 * @return boolean
	 *
	 * @throws IllegalArgumentException
	 */
	public static function isFolder($entry)
	{
		if (!method_exists($entry, 'getDocumentModelName'))
		{
			throw new IllegalArgumentException('$entry', 'Must be a valid Document or website_MenuItem.');
		}
		return in_array($entry->getDocumentModelName(), self::$folderDocuments);
	}

	/**
	 * @param object $entry
	 * @return boolean
	 *
	 * @throws IllegalArgumentException
	 */
	public static function isPage($entry)
	{
		return !self::isFolder($entry);
	}


	/**
	 * @param array $folderDocuments
	 *
	 * @throws IllegalArgumentException
	 */
	public static function setFolderDocuments($folderDocuments)
	{
		if (!is_array($folderDocuments))
		{
			throw new IllegalArgumentException('folderDocuments Must be a valid array of document model names.');
		}
		self::$folderDocuments = $folderDocuments;
	}


	private static function getVisibility($document)
	{
		if (!is_object($document) || !method_exists($document, 'getNavigationVisibility') )
		{
			return WebsiteConstants::VISIBILITY_HIDDEN;
		}
		return $document->getNavigationVisibility();
	}


	/**
	 * Returns an associative array of useful (required!) pieces of information
	 * to build a menu. This is mainly used in the "change:menu" PHPTal tag.
	 *
	 * @return array<"id"=>integer,"ancestors"=>array,"indexOf"=>integer>
	 */
	public static function getCurrentPageAttributeForMenu()
	{
    	$ws = website_WebsiteModuleService::getInstance();
		$currentPageDoc = DocumentHelper::getDocumentInstance($ws->getCurrentPageId());
    	$currentPageAttr = array(
    		'id' => $ws->getCurrentPageId(),
    		'ancestors' => $ws->getCurrentPageAncestorsIds()
    		);

    	if ($currentPageDoc->getIsIndexPage())
    	{
    		$currentPageAttr['indexOf'] = $currentPageDoc->getDocumentService()->getParentOf($currentPageDoc)->getId();
    	}
    	return $currentPageAttr;
	}
}