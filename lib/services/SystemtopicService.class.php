<?php
/**
 * website_SystemtopicService
 * @package website
 */
class website_SystemtopicService extends website_TopicService
{
	/**
	 * @var website_SystemtopicService
	 */
	private static $instance;

	/**
	 * @return website_SystemtopicService
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
		return $this->pp->createQuery('modules_website/systemtopic');
	}
	
	/**
	 * Create a query based on 'modules_website/systemtopic' model.
	 * Only documents that are strictly instance of modules_website/systemtopic
	 * (not children) will be retrieved
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createStrictQuery()
	{
		return $this->pp->createQuery('modules_website/systemtopic', false);
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
			$reference = $document->getReference();
			website_WebsiteModuleService::getInstance()->setCurrentWebsiteId($this->getWebsiteId($document));
			$currenturl = LinkHelper::getDocumentUrl($reference);
			if (strpos($currenturl, 'action=ViewDetail') === false)
			{
				$urlrewriting = array('currenturl' => $currenturl);
			}
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
	 * @param website_persistentdocument_systemtopic $document
	 * @param string $lang
	 * @param array $parameters
	 */
	public function generateUrl($document, $lang, $parameters)
	{
		return LinkHelper::getDocumentUrl($document->getReference(), $lang, $parameters);
	}
	
	/**
	 * @param website_persistentdocument_systemtopic $document
	 * @return Boolean
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