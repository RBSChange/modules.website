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
			self::$instance = self::getServiceClassInstance(get_class());
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
	}
	
	/**
	 * @param website_persistentdocument_pagegroup $pagegroup
	 * @param Integer $versionId
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
		$this->pp->updateDocument($pagegroup);
		
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
		}
		else
		{
			$oldPublicationStatus = $pagegroup->getPublicationstatus();
			$version->copyPropertiesListTo($pagegroup, self::$propertiesNames, $lang == $pagegroup->getLang());
			$pagegroup->setCurrentversionid($version->getId());

			if ($pagegroup->isModified())
			{
				try
				{
					$this->tm->beginTransaction();

					$this->pp->updateDocument($pagegroup);

					$this->synchronizeReferences($pagegroup);

					$this->tm->commit();
				}
				catch (Exception $e)
				{
					$this->tm->rollBack($e);
				}

				$newPublicationStatus = $pagegroup->getPublicationstatus();

				// Dispatch event
				f_event_EventManager::dispatchEvent('persistentDocumentUpdated', $this, array("document" => $pagegroup));

				if ($oldPublicationStatus != $newPublicationStatus)
				{
					if ($newPublicationStatus == 'PUBLICATED')
					{
						$this->dispatchPublicationStatusChanged($pagegroup, $oldPublicationStatus, 'persistentDocumentPublished', array("cause" => "update"));
					}
					else if ($oldPublicationStatus == 'PUBLICATED')
					{
						$this->dispatchPublicationStatusChanged($pagegroup, $oldPublicationStatus, 'persistentDocumentUnpublished', array("cause" => "update"));
					}
				}
				else if ($newPublicationStatus == 'PUBLICATED')
				{
					$this->synchronizeReferences($pagegroup);
				}
			}
		}
	}
	
	/**
	 * @param website_persistentdocument_pagegroup $document
	 * @param string $moduleName
	 * @param string $treeType
	 * @param array<string, string> $nodeAttributes
	 */
	public function addTreeAttributes($document, $moduleName, $treeType, &$nodeAttributes)
	{
		parent::addTreeAttributes($document, $moduleName, $treeType, $nodeAttributes);
		
		$nodeAttributes['_follow_children'] = true;
		$currentVersionId = intval($document->getCurrentversionid());
		if ($currentVersionId != 0)
		{
			$nodeAttributes['related-id'] = $currentVersionId;
			$nodeAttributes['related-type'] = 'modules_website_pageversion';
		}
		else
		{
			$nodeAttributes['related-id'] = - 1;
		}
	}
}