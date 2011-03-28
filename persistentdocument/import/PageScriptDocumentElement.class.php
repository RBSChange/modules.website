<?php
class website_PageScriptDocumentElement extends import_ScriptDocumentElement
{
	private $isHomePage = false;
	private $isIndexPage = false;
	
	/**
	 * @return f_persistentdocument_PersistentDocument
	 */
	protected function initPersistentDocument()
	{
		return website_PageService::getInstance()->getNewDocumentInstance();
	}
	
	protected function getDocumentProperties()
	{
		$properties = parent::getDocumentProperties();
		
		
		$page = $this->getPersistentDocument();
		if ($page->isNew())
		{
			if (!isset($properties['template']))
			{
				if (isset($properties['template-attr']) && $properties['template-attr'] != '')
				{
					$template = $this->getAncestorAttribute($properties['template-attr']);
				}
				else
				{
					$template = $this->getAncestorAttribute('template');
				}
				if ($template !== null)
				{
					$properties['template'] = $template;
				}
			}
		}
		if (isset($properties['url']))
		{
			$page->url = $properties['url'];
		}
		if (isset($properties['label']))
		{
			if (!isset($properties['navigationtitle']))
			{
				$properties['navigationtitle'] = $properties['label'];
			}
			if (!isset($properties['metatitle']))
			{
				$properties['metatitle'] = $properties['label'];
			}
		}
		// This must be done if the document is not new to be able to update an ACTIVE/PUBLISHED/DEACTIVATED page.
		if (in_array($page->getPublicationstatus(), array('DRAFT', 'ACTIVE', 'PUBLICATED', 'DEACTIVATED')))
		{
			if (!isset($properties['publicationstatus']))
			{
				$properties['publicationstatus'] = 'DRAFT';
			}
		}
		
		// Handle xxx-<lang> attributes.
		$this->getDocumentLocalizedProperties($properties, $page);
		
		if (isset($properties['isHomePage']))
		{
			$this->isHomePage = self::parseBoolean($properties['isHomePage']);
			unset($properties['isHomePage']);
		}
		
		if (isset($properties['isIndexPage']))
		{
			$this->isIndexPage = self::parseBoolean($properties['isIndexPage']);
			unset($properties['isIndexPage']);
		}
		
		if (isset($properties['navigationVisibility']) && !is_numeric($properties['navigationVisibility']))
		{
			if ($properties['navigationVisibility'] == 'visible')
			{
				$properties['navigationVisibility'] = WebsiteConstants::VISIBILITY_VISIBLE;
			}
			elseif ($properties['navigationVisibility'] == 'hidden')
			{
				$properties['navigationVisibility'] = WebsiteConstants::VISIBILITY_HIDDEN;
			}
			else
			{
				$properties['navigationVisibility'] = WebsiteConstants::VISIBILITY_HIDDEN_IN_MENU_ONLY;
			}
		}
		
		return $properties;
	}
	
	public function process()
	{
		parent::process();
		if ($this->isHomePage)
		{
			website_WebsiteModuleService::getInstance()->setHomePage($this->getPersistentDocument());
		}
		
		if ($this->isIndexPage)
		{
			website_WebsiteModuleService::getInstance()->setIndexPage($this->getPersistentDocument());
		}
	}
	
	public function endProcess()
	{
		$document = $this->getPersistentDocument();
		$rc = RequestContext::getInstance();
		foreach ($rc->getSupportedLanguages() as $lang)
		{
			if ($document->isLangAvailable($lang))
			{
				$rc->beginI18nWork($lang);
				if (!$document->getContent())
				{
					$document->getDocumentService()->setDefaultContent($document);
				}
				if ($document->getPublicationstatus() == 'DRAFT')
				{
					$document->getDocumentService()->activate($document->getId());
				}
				$rc->endI18nWork();
			}
		}
	}
	
	/**
	 * @return void
	 */
	protected function saveDocument()
	{
		parent::saveDocument();
		$document = $this->getPersistentDocument();
		if (isset($document->url))
		{
			$document->getDocumentService()->setUrlRewriting($document, $document->getLang(), $document->url);
		}
	}
	
	/**
	 * @param Array<String, Mixed> $properties
	 * @param website_persistentdocument_page $page
	 */
	private function getDocumentLocalizedProperties(&$properties, $page)
	{
		$rc = RequestContext::getInstance();
		foreach ($rc->getSupportedLanguages() as $lang)
		{
			try
			{
				$rc->beginI18nWork($lang);
				if (!($page->isLangAvailable($lang)))
				{
					if (isset($properties['label-'.$lang]))
					{
						if (!isset($properties['navigationtitle-'.$lang]))
						{
							$properties['navigationtitle-'.$lang] = $properties['label-'.$lang];
						}
						if (!isset($properties['metatitle-'.$lang]))
						{
							$properties['metatitle-'.$lang] = $properties['label-'.$lang];
						}
					}
				}
				// This must be done if the document is not new to be able to update an ACTIVE/PUBLISHED/DEACTIVATED page.
				else if (in_array($page->getPublicationstatus(), array('DRAFT', 'ACTIVE', 'PUBLICATED', 'DEACTIVATED')))
				{
					if (!isset($properties['publicationstatus-'.$lang]))
					{
						$properties['publicationstatus-'.$lang] = 'DRAFT';
					}
				}
				// In case of invalid status, throw an exception.
				else
				{
					throw new Exception('Invalid page status! (id = ' . $page->getId() . ', status = ' . $page->getPublicationstatus() . ')');
				}
				$rc->endI18nWork();
			}
			catch (Exception $e)
			{
				$rc->endI18nWork($e);
			}
		}
	}
	
	/**
	 * @param import_ScriptExecuteElement $scriptExecute
	 */
	public function setPageRefAsIndex($scriptExecute)
	{
		$page = $this->getPersistentDocument();
		$refs = website_PagereferenceService::getInstance()->getPagesReferenceByPage($page);
		foreach ($refs as $pageRef) 
		{
			if (!$pageRef->getIsIndexPage())
			{
				Framework::info(__METHOD__ . ': ' . $pageRef->__toString());
				website_WebsiteModuleService::getInstance()->setIndexPage($pageRef, true);
			}
		}
	}
}