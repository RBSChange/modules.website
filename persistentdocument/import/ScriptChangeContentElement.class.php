<?php
class website_ScriptChangeContentElement extends import_ScriptBaseElement
{
	public function endProcess()
	{
		$children = $this->script->getChildren($this);
		if (count($children))
		{
			$parent = $this->getParent();
			if ($parent instanceof import_ScriptDocumentElement)
			{
				$page = $parent->getPersistentDocument();
				$rc = RequestContext::getInstance();
				if (isset($this->attributes['lang']) && in_array($this->attributes['lang'], $rc->getSupportedLanguages()))
				{
					$rc->beginI18nWork($this->attributes['lang']);
					if ($page->getContent() === null)
					{
						website_PageService::getInstance()->setDefaultContent($page);
					}
					$this->updateContent($page, $children);
					$page->save();
					$rc->endI18nWork();
				}				
				else
				{
					$this->updateContent($page, $children);
					$page->save();
				}				
			}
		}
	}
	
	/**
	 * @param website_persistentdocument_page $page
	 * @return String
	 */
	private function getContentZoneId($page)
	{
		if (isset($this->attributes['for']))
		{
			$forContentZoneId = $this->attributes['for'];
		}
		else
		{
			$contentZones = theme_PagetemplateService::getInstance()->getChangeContentIds($page->getTemplate());
			if (f_util_ArrayUtils::isEmpty($contentZones))
			{
				throw new Exception("No insertion point defined in template");
			}
			$forContentZoneId = f_util_ArrayUtils::firstElement($contentZones);
		}
		return $forContentZoneId;
	}
	
	/**
	 * @param String $id
	 * @param DOMDocument $document
	 * @return DOMElement
	 */
	private function createChangeContent($id, $document)
	{
		$changeContent = $document->createElementNS(website_PageService::CHANGE_PAGE_EDITOR_NS, 'content');
		$changeContent->setAttribute("id", $id);
		$document->getElementsByTagNameNS(website_PageService::CHANGE_PAGE_EDITOR_NS, 'contents')->item(0)->appendChild($changeContent);
		return $changeContent;
	}
	
	/**
	 * @param DOMNode $parentNode
	 * @param DOMDocument $document
	 * @return DOMElement
	 */
	private function createChangeLayoutAndCol($parentNode, $document)
	{
		$newLayout = $document->createElementNS(website_PageService::CHANGE_PAGE_EDITOR_NS, "layout");
		$newColumn = $document->createElementNS(website_PageService::CHANGE_PAGE_EDITOR_NS, "col");
		$newColumn->setAttribute("widthPercentage", "100");
		$newLayout->appendChild($newColumn);
		$parentNode->appendChild($newLayout);
		return $newColumn;
	}
	
	/**
	 * @param website_persistentdocument_page $page
	 * @param array<website_ScriptBlockElement> $children
	 */
	private function updateContent($page, $children)
	{
		$document = new DOMDocument('1.0', 'UTF-8');
		$document->loadXML($page->getContent());
		$forContentZoneId = $this->getContentZoneId($page);
		
		$xpath = new DOMXPath($document);
		$xpath->registerNameSpace('change', website_PageService::CHANGE_PAGE_EDITOR_NS);
		$entries = $xpath->query('//change:content[@id = "' . $forContentZoneId . '"]');
		
		// Create a change:content element if none already found.
		if ($entries->length == 0)
		{
			$changeContent = $this->createChangeContent($forContentZoneId, $document);
		}
		else
		{
			$changeContent = $entries->item(0);
			if (!isset($this->attributes['append']) || $this->attributes['append'] != 'true')
			{
				$changeContent->parentNode->removeChild($changeContent);
				$changeContent = $this->createChangeContent($forContentZoneId, $document);
			}			
		}
		
		$insertionPoint = null;
		foreach ($children as $scriptElement)
		{
			if ($scriptElement instanceof website_ScriptChangeBlockElement)
			{
				if ($insertionPoint == null)
				{
					$insertionPoint = $this->createChangeLayoutAndCol($changeContent, $document);
				}
				$newRow = $document->createElementNS(website_PageService::CHANGE_PAGE_EDITOR_NS, "row");
				$newBloc = $scriptElement->generateBlock($document);
				$newRow->appendChild($newBloc);
				$insertionPoint->appendChild($newRow);
			}
			else if ($scriptElement instanceof website_ScriptChangeLayoutElement)
			{
				$insertionPoint = null;
				$newLayout = $scriptElement->generateLayout($document);
				$changeContent->appendChild($newLayout);
			}
		}
		$finalContent = $document->saveXML($document->documentElement);
		website_PageService::getInstance()->updatePageContent($page, $finalContent);
	}
}