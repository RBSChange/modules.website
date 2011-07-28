<?php
class website_PageexternalService extends f_persistentdocument_DocumentService
{
	/**
	 * @var website_PageexternalService
	 */
	private static $instance;

	/**
	 * @return website_PageexternalService
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
	 * @return website_persistentdocument_pageexternal
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_website/pageexternal');
	}

	/**
	 * Create a query based on 'modules_website/pageexternal' model
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->pp->createQuery('modules_website/pageexternal');
	}

	/**
	 * Returns TRUE if the given Page is publishable.
	 *
	 * @param website_persistentdocument_pageexternal $page
	 * @return boolean
	 */
	public function isPublishable($page)
	{
	    return $page->getUrl() && parent::isPublishable($page);
	}

	/**
	 * @param website_persistentdocument_pageexternal $document
	 * @param Integer $destId
	 */
	public function onMoveToStart($document, $destId)
	{
		// Remove index page
		if ($document instanceof website_persistentdocument_pageexternal && $document->getIsIndexPage())
		{
			website_WebsiteModuleService::getInstance()->removeIndexPage($document);
		}
	}
	
	/**
	 * @param website_persistentdocument_pageexternal $pageExternal
	 * @param Boolean $isIndexPage
	 * @param Boolean $userSetting
	 */
	public function setIsIndexPage($pageExternal, $isIndexPage, $userSetting = false)
	{
		try
		{
			$this->tm->beginTransaction();
			$pageExternal->setIsIndexPage($isIndexPage);    
	    	if ($pageExternal->isModified())
			{
				$this->pp->updateDocument($pageExternal);
			}
			$this->tm->commit();
		}
		catch (Exception $e)
		{
			$this->tm->rollBack($e);
		}
	}
	
	/**
	 * Add custom log informations
	 * @param website_persistentdocument_pageexternal $document
	 * @param string $actionName
	 * @param array $info
	 */
	public function addActionLogInfo($document, $actionName, &$info)
	{
		$pageNode = TreeService::getInstance()->getInstanceByDocument($document);
		if ($pageNode === null)
		{
			$info['path'] = '';
			return;
		}
		$path = array();
		foreach ($pageNode->getAncestors() as $node) 
		{
			$doc = $node->getPersistentDocument();
			if ($doc instanceof website_persistentdocument_website || $doc instanceof website_persistentdocument_topic) 
			{
				$path[] = $doc->getLabel();
			}
		}
		$info['path'] = implode(' / ', $path);
	}
	
	/**
	 * @param website_persistentdocument_pageexternal $document
	 * @return String
	 */
	public function generateUrl($document)
	{
		if ($document->getUseurl())
		{
			return $document->getUrl();
		}
		$navigationtitle = strtolower(website_UrlRewritingService::getInstance()->encodePathString($document->getNavigationtitle()));
		return LinkHelper::getActionUrl('website', 'ViewPageexternal', 
			array('cmpref' => $document->getId(), 
			'navigationtitle' => $navigationtitle, 
			'lang' => RequestContext::getInstance()->getLang()));
	}
}