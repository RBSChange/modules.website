<?php
class website_PagegroupService extends website_PageService
{
	/**
	 * @var website_PagegroupService
	 */
	private static $instance;
	
	private static $propertiesNames = array('label', 'author', 'creationdate', 'publicationstatus', 'modelversion', 'startpublicationdate', 'endpublicationdate', 'navigationtitle', 'metatitle', 'description', 'keywords', 'indexingstatus', 'template', 'content', 'skin', 'navigationVisibility', 'isIndexPage', 'isHomePage', 'advancedreferencing', 'robotsmeta');
	
	
	/**
	 * @return website_PagegroupService
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	/**
	 * @return website_persistentdocument_pagegroup
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_website/pagegroup');
	}
	
	/**
	 * Create a query based on 'modules_modules_website/pagegroup' model
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->pp->createQuery('modules_website/pagegroup');
	}
	
	/**
	 * @param website_persistentdocument_pagegroup $newDocument
	 * @param website_persistentdocument_pagegroup $originalDocument
	 * @param Integer $parentNodeId
	 */
	protected function preDuplicate($newDocument, $originalDocument, $parentNodeId)
	{
		throw new IllegalOperationException('This document cannot be duplicated.');
		/*
		$requestContext = RequestContext::getInstance();		
		foreach ($requestContext->getSupportedLanguages() as $lang)
		{
			try
			{
				$requestContext->beginI18nWork($lang);
				if ($newDocument->isContextLangAvailable())
				{
					$newDocument->setCurrentversionid(0);
				}
				$requestContext->endI18nWork();
			} 
			catch (Exception $e)
			{
				$requestContext->endI18nWork($e);
			}
		}
		parent::preDuplicate($newDocument, $originalDocument, $parentNodeId);
		*/
	}
	
	/**
	 * @param website_persistentdocument_pagegroup $pagegroup
	 * @param Integer $versionId
	 */
	public function setCurrentVersion($pagegroup, $chooserName = 'publicated')
	{
		if (Framework::isDebugEnabled())
		{
			Framework::debug(__METHOD__ . '->' . $pagegroup->__toString());
		}
		
		$requestContext = RequestContext::getInstance();
		$className = 'website_Pagegroup' . ucfirst($chooserName) . 'Chooser';
		$chooser = f_util_ClassUtils::callMethod($className, 'getInstance');
		$versions = $pagegroup->getChildrenVersions();
		$langs = $requestContext->getSupportedLanguages();
		foreach ($langs as $lang)
		{
			try
			{
				$requestContext->beginI18nWork($lang);
				$this->setCurrentVersionForLang($pagegroup, $versions, $chooser);
				$requestContext->endI18nWork();
			}
			catch (Exception $e)
			{
				$requestContext->endI18nWork($e);
			}
		
		}
	}
	
	/**
	 * @param website_persistentdocument_pagegroup $pagegroup
	 */
	public function removeCurrentVersion($pagegroup)
	{
		if (Framework::isDebugEnabled())
		{
			Framework::debug(__METHOD__ . ' ' . $pagegroup->__toString());
		}
		
		$requestContext = RequestContext::getInstance();
		$langs = $requestContext->getSupportedLanguages();
		foreach ($langs as $lang)
		{
			try
			{
				$requestContext->beginI18nWork($lang);
				if ($pagegroup->isContextLangAvailable())
				{
					$pagegroup->setCurrentversionid(0);
				}
				$requestContext->endI18nWork();
			} 
			catch (Exception $e)
			{
				$requestContext->endI18nWork($e);
			}
		}
		$this->pp->updateDocument($pagegroup);
		
		$page = $this->transform($pagegroup, 'modules_website/page');
		$page->getDocumentService()->publishDocumentIfPossible($page, array('cause' => 'DELETE'));
	}
	
	/**
	 * @param website_persistentdocument_pagegroup $pagegroup
	 * @param array<website_persistentdocument_pageversion> $versions
	 * @param website_PagegroupPublicatedChooser $chooser
	 */
	private function setCurrentVersionForLang($pagegroup, $versions, $chooser)
	{
		$version = $chooser->select($versions);
		if (is_null($version))
		{
			if ($pagegroup->isContextLangAvailable())
			{
				$pagegroup->delete();
			}
		} else
		{
			if (Framework::isDebugEnabled())
			{
				$lang = RequestContext::getInstance()->getLang();
				Framework::debug("Page " . $pagegroup->__toString() . "in ($lang) is set to version" . $version->__toString());
			}

			$oldPublicationStatus = $pagegroup->getPublicationstatus();

			$version->copyPropertiesListTo($pagegroup, self::$propertiesNames, false);
			$pagegroup->setCurrentversionid($version->getId());

			if ($pagegroup->isModified())
			{
				if (Framework::isDebugEnabled())
				{
					Framework::debug("Save page info " . $pagegroup->__toString());
				}
				try
				{
					$this->tm->beginTransaction();

					$this->pp->updateDocument($pagegroup);

					$this->synchronizeReferences($pagegroup);

					$this->tm->commit();
				} catch (Exception $e)
				{
					$this->tm->rollBack($e);
				}

				$newPublicationStatus = $pagegroup->getPublicationstatus();

				/**
				 * Dispatch event
				 */
				f_event_EventManager::dispatchEvent('persistentDocumentUpdated', $this, array("document" => $pagegroup));

				if ($oldPublicationStatus != $newPublicationStatus)
				{
					if ($newPublicationStatus == 'PUBLICATED')
					{
						$this->dispatchPublicationStatusChanged($pagegroup, $oldPublicationStatus, 'persistentDocumentPublished', array("cause" => "update"));
					} else if ($oldPublicationStatus == 'PUBLICATED')
					{
						$this->dispatchPublicationStatusChanged($pagegroup, $oldPublicationStatus, 'persistentDocumentUnpublished', array("cause" => "update"));
					}
				} else if ($newPublicationStatus == 'PUBLICATED')
				{
					$this->synchronizeReferences($pagegroup);
				}
			}
		}
	}
	
	/**
	 * @param website_persistentdocument_pagegroup $document
	 * @param array<string, string> $attributes
	 * @param integer $mode
	 * @param string $moduleName
	 */
	public function completeBOAttributes($document, &$attributes, $mode, $moduleName)
	{
		parent::completeBOAttributes($document, $attributes, $mode, $moduleName);
		if ($document->getIsHomePage())
		{
			$attributes['icon'] = 'page-group-home';
		} 
		elseif ($document->getIsIndexPage())
		{
			$attributes['icon'] = 'page-group-index';
		}
	}
}