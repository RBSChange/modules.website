<?php
/**
 * website_BlockConfigurableMenuAction
 * @package modules.website.lib.blocks
 */
class website_BlockConfigurableMenuAction extends website_BlockAction
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
	
		// Look for root document.
		$config = $this->getConfiguration();
		switch ($config->getMode())
		{
			case 'document':
				$doc = $config->getCmpref();
				break;
				
			case 'tag':
				$website = website_WebsiteService::getInstance()->getCurrentWebsite();
				$doc = TagService::getInstance()->getDocumentByContextualTag($config->getTag(), $website);
				break;
				
			case 'contextual':
				$ancestorIds = $this->getContext()->getAncestorIds();
				$startLevel = $config->getStartLevel();
				if (count($ancestorIds) <= $startLevel)
				{
					return website_BlockView::NONE;
				}
				$doc = DocumentHelper::getDocumentInstance($ancestorIds[$startLevel]);
				break;
		}
		
		$root = $this->getRootMenuEntry($doc, 0, $config->getDepth());
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
	 * @return website_MenuEntry|null
	 * 
	 */
	protected function getRootMenuEntry($doc, $level, $maxLevel)
	{
		$wms = website_PageService::getInstance();
		$currentId = $wms->getCurrentPageId();
		$ancestorIds = $wms->getCurrentPageAncestorsIds();
		$deployAll = $this->getConfiguration()->getDeployAll();
		return $this->getMenuEntries($doc, $level, $maxLevel, $currentId, $ancestorIds, $deployAll);
	}
	
	/**
	 * @param f_persistentdocument_PersistentDocument $doc
	 * @param integer $level
	 * @param integer $maxLevel
	 * @param integer $currentId
	 * @param integer[] $ancestorIds
	 * @param boolean $deployAll
	 * @return website_MenuEntry|null
	 */
	protected function getMenuEntries($doc, $level, $maxLevel, $currentId, $ancestorIds, $deployAll)
	{
		if ($doc === null)
		{
			return null;
		}
		$service = $doc->getDocumentService();
		if (!$doc->isPublished() || !f_util_ClassUtils::methodExists($service, 'getMenuEntry'))
		{
			return null;
		}
		
		// Generate the entry.
		$entry = $service->getMenuEntry($doc);
		if ($entry === null) 
		{
			return null;
		}
		/* @var $entry website_MenuEntry */
		$entry->setLevel($level);
		
		$doc = $entry->getDocument(); // For menuitem documents $doc may differ from $entry->getDocument().
		$docId = $entry->getDocument()->getId(); 
		$isCurrent = ($currentId == $docId);
		$inPath = in_array($docId, $ancestorIds);
		$entry->setCurrent($isCurrent);
		$entry->setInPath($inPath);
		
		// Generate children entries.
		if ($entry->isContainer() && $level < $maxLevel && ($deployAll || $inPath || $isCurrent))
		{
			$children = array();
			foreach ($doc->getDocumentService()->getChildrenDocumentsForMenu($doc) as $childDoc)
			{
				$childEntry = $this->getMenuEntries($childDoc, $level+1, $maxLevel, $currentId, $ancestorIds, $deployAll);
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