<?php
/**
 * website_BlockConfigurableSitemapAction
 * @package modules.website.lib.blocks
 */
class website_BlockConfigurableSitemapAction extends website_BlockAction
{
	/**
	 * @param f_mvc_Request $request
	 * @param f_mvc_Response $response
	 * @return string
	 */
	public function execute($request, $response)
	{
		if ($this->isInBackofficeEdition())
		{
			return website_BlockView::NONE;
		}
	
		$config = $this->getConfiguration();
		$website = website_WebsiteService::getInstance()->getCurrentWebsite();
		$root = $this->getRootMenuEntry($website, 0, $config->getDepth());
		if ($root === null)
		{
			return website_BlockView::NONE;
		}

		// Handle title configuration.
		if ($config->getShowTitle())
		{
			$title = $config->getBlockTitle();
			if ($title)
			{
				$title = str_replace('{ROOT_LABEL}', $root->getLabel(), $title);
				$root->setLabel($title);
			}
		}
				
		$request->setAttribute('root', $root);
		$request->setAttribute('menuClass', strtolower($config->getDisplayMode()));
		
		return ucfirst($config->getDisplayMode());
	}
	
	/**
	 * @param f_persistentdocument_PersistentDocument $doc
	 * @param integer $level
	 * @param integer $maxLevel
	 */
	protected function getRootMenuEntry($doc, $level, $maxLevel)
	{
		$wms = website_PageService::getInstance();
		$currentId = $wms->getCurrentPageId();
		$ancestorIds = $wms->getCurrentPageAncestorsIds();
		return $this->getMenuEntries($doc, $level, $maxLevel, $currentId, $ancestorIds);
	}
	
	/**
	 * @param f_persistentdocument_PersistentDocument $doc
	 * @param integer $level
	 * @param integer $maxLevel
	 * @param integer $currentId
	 * @param integer[] $ancestorIds
	 * @return website_MenuEntry|null
	 */
	protected function getMenuEntries($doc, $level, $maxLevel, $currentId, $ancestorIds)
	{
		if ($doc === null)
		{
			return null;
		}
		$service = $doc->getDocumentService();
		if (!$doc->isPublished() || !f_util_ClassUtils::methodExists($service, 'getSitemapEntry'))
		{
			return null;
		}
		
		// Generate the entry.
		$entry = $service->getSitemapEntry($doc);
		if ($entry === null)
		{
			return null;
		}
		/* @var $entry website_MenuEntry */
		$entry->setLevel($level);
	
		$doc = $entry->getDocument(); // For menuitem documents $doc may differ from $entry->getDocument().
		$docId = $entry->getDocument()->getId();
		$entry->setCurrent($currentId == $docId);
		$entry->setInPath(in_array($docId, $ancestorIds));
	
		// Generate children entries.
		if ($entry->isContainer() && $level < $maxLevel)
		{
			$children = array();
			foreach ($doc->getDocumentService()->getChildrenDocumentsForMenu($doc) as $childDoc)
			{
				$childEntry = $this->getMenuEntries($childDoc, $level+1, $maxLevel, $currentId, $ancestorIds);
				if ($childEntry !== null)
				{
					$children[] = $childEntry;
				}
			}
			$entry->setChildren($children);
		}
		return $entry;
	}
}