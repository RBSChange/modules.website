<?php
/**
 * @package modules.website
 * @method website_PagegroupService getInstance()
 */
class website_PagegroupService extends website_PageService
{
	/**
	 * @var string[]
	 */
	private static $propertiesNames = array('label', 'author', 'creationdate', 'publicationstatus', 'modelversion', 'startpublicationdate', 'endpublicationdate', 'navigationtitle', 'metatitle', 'description', 'keywords', 'indexingstatus', 'template', 'content', 'skin', 'navigationVisibility', 'isIndexPage', 'isHomePage', 'advancedreferencing', 'robotsmeta');

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
		return $this->getPersistentProvider()->createQuery('modules_website/pagegroup');
	}
	
	/**
	 * @param website_persistentdocument_pagegroup $newDocument
	 * @param website_persistentdocument_pagegroup $originalDocument
	 * @param integer $parentNodeId
	 */
	protected function preDuplicate($newDocument, $originalDocument, $parentNodeId)
	{
		throw new IllegalOperationException('This document cannot be duplicated.');
	}
	
	/**
	 * @param website_persistentdocument_pagegroup $pagegroup
	 * @param integer $versionId
	 */
	public function setCurrentVersion($pagegroup, $chooserName = 'publicated')
	{
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
	 * @param website_persistentdocument_pageversion[] $versions
	 */
	public function removeCurrentVersion($pagegroup, $versions = null)
	{
		if (Framework::isDebugEnabled())
		{
			Framework::debug(__METHOD__ . ' ' . $pagegroup->__toString());
		}
		
		$requestContext = RequestContext::getInstance();
		foreach ($pagegroup->getI18nInfo()->getLangs() as $lang)
		{
			try
			{
				$requestContext->beginI18nWork($lang);
				$pagegroup->setCurrentversionid(0);
				$requestContext->endI18nWork();
			} 
			catch (Exception $e)
			{
				$requestContext->endI18nWork($e);
			}
		}
		$this->getPersistentProvider()->updateDocument($pagegroup);
		
		$page = $this->transform($pagegroup, 'modules_website/page');
		$page->getDocumentService()->publishDocumentIfPossible($page, array('cause' => 'DELETE'));
		
		if (is_array($versions))
		{
			foreach ($versions as $version)
			{
				$this->purgeDocument($version);
			}
		}
	}
	
	/**
	 * @param website_persistentdocument_pagegroup $pagegroup
	 * @param array<website_persistentdocument_pageversion> $versions
	 * @param website_PagegroupPublicatedChooser $chooser
	 */
	private function setCurrentVersionForLang($pagegroup, $versions, $chooser)
	{
		$lang = RequestContext::getInstance()->getLang();
		$version = $chooser->select($versions);
		if (is_null($version))
		{
			if ($pagegroup->isContextLangAvailable())
			{
				$pagegroup->delete();
			}
		} else
		{
			$oldPublicationStatus = $pagegroup->getPublicationstatus();
			$version->copyPropertiesListTo($pagegroup, self::$propertiesNames, $lang == $pagegroup->getLang());
			$pagegroup->setCurrentversionid($version->getId());

			if ($pagegroup->isModified())
			{
				try
				{
					$this->getTransactionManager()->beginTransaction();

					$this->getPersistentProvider()->updateDocument($pagegroup);

					$this->synchronizeReferences($pagegroup);

					$this->getTransactionManager()->commit();
				}
				catch (Exception $e)
				{
					$this->getTransactionManager()->rollBack($e);
				}

				$newPublicationStatus = $pagegroup->getPublicationstatus();

				// Dispatch event.
				f_event_EventManager::dispatchEvent('persistentDocumentUpdated', $this, array("document" => $pagegroup));

				if ($oldPublicationStatus != $newPublicationStatus)
				{
					if ($newPublicationStatus == 'PUBLISHED')
					{
						$this->dispatchPublicationStatusChanged($pagegroup, $oldPublicationStatus, 'persistentDocumentPublished', array("cause" => "update"));
					} 
					else if ($oldPublicationStatus == 'PUBLISHED')
					{
						$this->dispatchPublicationStatusChanged($pagegroup, $oldPublicationStatus, 'persistentDocumentUnpublished', array("cause" => "update"));
					}
				} 
				else if ($newPublicationStatus == 'PUBLISHED')
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