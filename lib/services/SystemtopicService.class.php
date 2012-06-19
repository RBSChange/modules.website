<?php
/**
 * @package modules.website
 * @method website_SystemtopicService getInstance()
 */
class website_SystemtopicService extends website_TopicService
{
	/**
	 * @return website_persistentdocument_systemtopic
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_website/systemtopic');
	}

	/**
	 * Create a query based on 'modules_website/systemtopic' model.
	 * Return document that are instance of modules_website/systemtopic,
	 * including potential children.
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->getPersistentProvider()->createQuery('modules_website/systemtopic');
	}
	
	/**
	 * Create a query based on 'modules_website/systemtopic' model.
	 * Only documents that are strictly instance of modules_website/systemtopic
	 * (not children) will be retrieved
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createStrictQuery()
	{
		return $this->getPersistentProvider()->createQuery('modules_website/systemtopic', false);
	}
	
	/**
	 * @param integer $referenceId
	 * @return website_persistentdocument_systemtopic[]
	 */
	public function getByReferenceId($referenceId)
	{
		return $this->createQuery()->add(Restrictions::eq('referenceId', $referenceId))->find();
	}
	
	/**
	 * @param website_persistentdocument_systemtopic $document
	 * @param string $forModuleName
	 * @param array $allowedSections
	 * @return array
	 */
	public function getResume($document, $forModuleName, $allowedSections = null)
	{	
		$urlrewriting = null;
		if (($allowedSections === null && $document->getPersistentModel()->hasURL()) || isset($allowedSections['urlrewriting']))
		{
			$currenturl = LinkHelper::getDocumentUrl($document);
			$urlrewriting = array('currenturl' => $currenturl);
		}

		$data = parent::getResume($document, $forModuleName, array('properties' => true, 'publication' => true, 'localization' => true, 'history' => true));
		$rc = RequestContext::getInstance();
		$contextlang = $rc->getLang();
		$usecontextlang = $document->isLangAvailable($contextlang);
		$lang = $usecontextlang ? $contextlang : $document->getLang();
		try
		{
			$rc->beginI18nWork($lang);
				
			// Informations.
			$reference = $document->getReference();
			if ($reference !== null)
			{
				$data['properties']['reference'] = $reference->getLabel() . ' (' . f_Locale::translateUI($reference->getPersistentModel()->getLabel()) . ' - ' . $reference->getId() . ')';
			}
			else
			{
				Framework::fatal(__METHOD__ . $document->__toString());
			}
			
			$rc->endI18nWork();
		}
		catch (Exception $e)
		{
			$rc->endI18nWork($e);
		}
		if ($urlrewriting !== null)
		{
			$data['urlrewriting'] = $urlrewriting;
		}
		return $data;
	}
	
	/**
	 * @param website_UrlRewritingService $urlRewritingService
	 * @param website_persistentdocument_systemtopic $document
	 * @param website_persistentdocument_website $website
	 * @param string $lang
	 * @param array $parameters
	 * @return f_web_Link | null
	 */
	public function getWebLink($urlRewritingService, $document, $website, $lang, $parameters)
	{
		$reference = $document->getReference();
		if ($reference !== null)
		{
			$website = website_persistentdocument_website::getInstanceById($this->getWebsiteId($document));
			$ds = $reference->getDocumentService();
			if (f_util_ClassUtils::methodExists($ds, 'getWebLinkForSystemTopic'))
			{
				return $ds->getWebLinkForSystemTopic($urlRewritingService, $reference, $document, $lang, $parameters);
			}
			else
			{
				return $urlRewritingService->getDocumentLinkForWebsite($reference, $website, $lang, $parameters);
			}	
		}		
		return null;
	}
	
	/**
	 * @param website_persistentdocument_website $website
	 * @param string $lang
	 * @param string $modelName
	 * @param integer $offset
	 * @param integer $chunkSize
	 * @return website_persistentdocument_systemtopic[]
	 */
	public function getDocumentForSitemap($website, $lang, $modelName, $offset, $chunkSize)
	{
		return array();
	}
	
	/**
	 * @param website_persistentdocument_systemtopic $document
	 * @return boolean
	 */
	public function isPublishable($document)
	{
		$reference = $document->getReference();
		if ($reference === null)
		{
			return false;
		}
		$ds = $reference->getDocumentService();
		if (f_util_ClassUtils::methodExists($ds, 'isSystemtopicPublishable'))
		{
			return $ds->isSystemtopicPublishable($reference, $document);
		}
		return parent::isPublishable($document);
	}
}