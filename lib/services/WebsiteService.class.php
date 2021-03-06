<?php
class website_WebsiteService extends f_persistentdocument_DocumentService
{
	/**
	 * @var website_WebsiteService
	 */
	private static $instance;

	/**
	 * @return website_WebsiteService
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
	 * @return website_persistentdocument_website
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_website/website');
	}

	/**
	 * Create a query based on 'modules_website/website' model
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->pp->createQuery('modules_website/website');
	}
	
	/**
	 * @param website_persistentdocument_website $document (Read only)
	 * @param array $defaultSynchroConfig string : string[]
	 * @return array string : string[]
	 */
	public function getI18nSynchroConfig($document, $defaultSynchroConfig)
	{
		return $document->getLocalizebypath() ? parent::getI18nSynchroConfig($document, $defaultSynchroConfig) : array();
	}
	
	/**
	 * @param website_persistentdocument_website $document
	 * @param Integer $parentNodeId Parent node ID where to save the document (optionnal).
	 * @return void
	 */
    protected function preSave($document, $parentNodeId)
    {     
        $document->setProtocol(Framework::getUIProtocol());
        $protocol = $document->getProtocol() . '://';
        $masterdomain = $document->getVoDomain();
        $localizebypath = $document->getLocalizebypath();
        $rc = RequestContext::getInstance(); 
        $voLang = $document->getLang();
        foreach ($document->getI18nInfo()->getLangs() as $lang) 
        {
        	try 
        	{
        		$rc->beginI18nWork($lang);       		 
	        	if ($localizebypath)
	        	{
	        		$document->setDomain($masterdomain);
	        		$document->setUrl($protocol.$masterdomain);
	        	}
	        	else if ($voLang !== $lang)
	        	{
	        		$subDomain = $document->getDomain();
	        		if ($masterdomain == $subDomain)
	        		{
	        			$subDomain = preg_replace('/\.' . $voLang . '$/', '.'.$lang, $subDomain);
	        		} 	        		
	        		if ($masterdomain == $subDomain)
	        		{
	        			$subDomain = $masterdomain . '.' . $lang;	
	        		}        		
	        		$document->setDomain($subDomain);		
        			$document->setUrl($protocol.$subDomain);
	        	}
	        	else
	        	{
	        		$document->setUrl($protocol.$masterdomain);
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
	 * @param website_persistentdocument_website $document
	 * @param Integer $parentNodeId Parent node ID where to save the document (optionnal).
	 * @return void
	 */
	protected function preInsert($document, $parentNodeId = null)
	{
		$rootFolderId = ModuleService::getInstance()->getRootFolderId('website');
		if (!is_null($parentNodeId) && $parentNodeId != $rootFolderId)
		{
			throw new Exception('Cannot insert a website into another website.');
		}
		
		if ($document->getRobottxt() == null)
		{
			$default = f_util_FileUtils::read(f_util_FileUtils::buildWebeditPath('media', 'frontoffice', 'robots.txt'));
			$document->setRobottxt($default);
		}
		
		if ($document->getTemplate())
		{
			$document->addAllowedpagetemplate(theme_persistentdocument_pagetemplate::getInstanceById($document->getTemplate()));
		}
		if ($document->getTemplateHome())
		{
			$document->addAllowedpagetemplate(theme_persistentdocument_pagetemplate::getInstanceById($document->getTemplateHome()));
		}
	}

	/**
	 * @param website_persistentdocument_website $document
	 * @param Integer $parentNodeId Parent node ID where to save the document (optionnal).
	 * @return void
	 */
	protected function postUpdate($document, $parentNodeId = null)
	{
		if ($document->getNewMarkerType() && $document->getNewMarkerAccount())
		{
			website_MarkerService::getInstance()->createNewMarker($document->getNewMarkerType(), $document->getNewMarkerAccount(), $document);
			$document->setNewMarkerAccount(null);
			$document->setNewMarkerType(null);
		}
	}

	/**
	 * @param website_persistentdocument_website $document
	 * @param Integer $parentNodeId Parent node ID where to save the document (optionnal).
	 * @return void
	 */
	protected function postInsert($document, $parentNodeId)
	{
		$query = $this->createQuery()->add(Restrictions::hasTag('default_modules_website_default-website'));

		// If we are creating the first website document, set it as the default
		// website.
		if (is_null($query->findUnique()) )
		{	
			website_WebsiteModuleService::getInstance()->setDefaultWebsite($document);
		}

		// Create the menus folder where the website's menus will be stored.
		$menuFolder = website_MenufolderService::getInstance()->getNewDocumentInstance();
		$menuFolder->save($document->getId());

		$initScript = $document->getStructureinit();
		if (f_util_StringUtils::isNotEmpty($initScript) && $document->getTemplate())
		{
			$this->generateDefaultStructure($document, $initScript);
		}
	}
		
	/**
	 * @param website_persistentdocument_website $website
	 * @param string $scriptPath
	 */
	private function generateDefaultStructure($website, $initScript)
	{
		$template = theme_persistentdocument_pagetemplate::getInstanceById($website->getTemplate())->getCodename();
		$attributes['template'] = $template;
		if ($website->getTemplateHome())
		{
			$attributes['templateHome'] = theme_persistentdocument_pagetemplate::getInstanceById($website->getTemplateHome())->getCodename();
		}
		else 
		{
			$attributes['templateHome'] = $template;
		}
		website_ModuleService::getInstance()->inititalizeStructure($website, 'website', $attributes, $initScript);
	}

	/**
	 * Handle Website deletion: deletes the folder that holds the menus and markers
	 *
	 * @param website_persistentdocument_website $document
	 */
	protected function preDelete($document)
	{
		website_MenufolderService::getInstance()->getInstance()->createQuery()
			->add(Restrictions::childOf($document->getId()))->delete();

		$markers = website_MarkerService::getInstance()->getAllByWebsite($document);
		foreach ($markers as $marker) 
		{
			$marker->delete();
		}	
		TreeService::getInstance()->setTreeNodeCache(false);
	}


	/**
	 * @param website_persistentdocument_website $website
	 * @return website_persistentdocument_menufolder
	 */
	public function getMenuFolder($website)
	{
		$nodeArray = TreeService::getInstance()->getInstanceByDocument($website)->getChildren('modules_website/menufolder');
		if (count($nodeArray) == 1)
		{
			return $nodeArray[0]->getPersistentDocument();
		}
		return null;
	}
	
	/**
	 * @param website_persistentdocument_website $website
	 * @param website_persistentdocument_page $newHomePage
	 */
	public function setHomePage($website, $newHomePage)
	{
	    if (!$website instanceof website_persistentdocument_website) 
	    {
	    	throw new IllegalArgumentException('website', 'website_persistentdocument_website');
	    }  
		try
		{
		    $this->tm->beginTransaction();
		    
            $oldPage = $website->getIndexPage();
      
            if ($oldPage !== null)
            {
               $oldPage->getDocumentService()->setIsHomePage($oldPage, false);
            }
            if ($newHomePage !== null)
            {
               $newHomePage->getDocumentService()->setIsHomePage($newHomePage, true);
            }
            
            $website->setIndexPage($newHomePage);
            $requestContext = RequestContext::getInstance();
			try 
            {
               	$requestContext->beginI18nWork($website->getLang());
               	$website->save();
               	$requestContext->endI18nWork();
            }
            catch (Exception $e)
            {
              	$requestContext->endI18nWork($e);
            }
            $this->tm->commit();
	    }
		catch (Exception $e)
		{
			$this->tm->rollBack($e);
		}          
	}
	
	/**
	 * @return Array<website_persistentdocument_website>
	 */
	public function getAll()
	{
		return $this->createQuery()->find();
	}
	
	/**
	 * @param Integer $descendentId
	 * @return website_persistentdocument_website
	 */
	public function getByDescendentId($descendentId)
	{
		return $this->createQuery()->add(Restrictions::ancestorOf($descendentId))->findUnique();
	}
	
	/**
	 * @param website_persistentdocument_website $document
	 * @param string $forModuleName
	 * @param array $allowedSections
	 * @return array
	 */
	public function getResume($document, $forModuleName, $allowedSections = null)
	{
		$data = parent::getResume($document, $forModuleName, $allowedSections);
		$rc = RequestContext::getInstance();
		$contextlang = $rc->getLang();
		$usecontextlang = $document->isLangAvailable($contextlang);
		$lang = $usecontextlang ? $contextlang : $document->getLang();
			
		try 
		{
			$rc->beginI18nWork($lang);
			if ($document->getLocalizebypath())
			{
				$data['urlrewriting']['currenturl'] = $document->getUrl() . '/' . $lang . '/'; 
			}
			else
			{
				$data['urlrewriting']['currenturl'] = $document->getUrl(). '/';
			}			
			$rc->endI18nWork();
		}
		catch (Exception $e)
		{
			$rc->endI18nWork($e);
		}			
		return $data;
	}
	
	/**
	 * @param website_persistentdocument_website $document
	 * @return integer
	 */
	public function getWebsiteId($document)
	{
		return $document->getId();
	}
	
	/**
	 * @param website_persistentdocument_website $document
	 * @return website_persistentdocument_page or null
	 */
	public function getDisplayPage($document)
	{
		return $document->getIndexPage();
	}

	/**
	 * @param website_UrlRewritingService $urlRewritingService
	 * @param website_persistentdocument_website $document
	 * @param website_persistentdocument_website $website
	 * @param string $lang
	 * @param array $parameters
	 * @return f_web_Link | null
	 */
	public function getWebLink($urlRewritingService, $document, $website, $lang, $parameters)
	{
		if ($document->getIndexPage())
		{
			$page = $document->getIndexPage();
			if ($page->getPublicationstatusForLang($lang) === f_persistentdocument_PersistentDocument::STATUS_PUBLISHED)
			{
				return $urlRewritingService->getDocumentLinkForWebsite($page, $website, $lang, $parameters);
			}
		}
		return null;
	}

	/**
	 * @param website_persistentdocument_website $document
	 * @return string|null
	 */
	public function getNavigationLabel($document)
	{
		$page = $document->getIndexPage();
		return ($page) ? $page->getNavigationLabel() : null;
	}
	
	/**
	 * @param website_persistentdocument_website $website
	 * @param string $lang
	 * @param string $modelName
	 * @param integer $offset
	 * @param integer $chunkSize
	 * @return website_persistentdocument_website[]
	 */
	public function getDocumentForSitemap($website, $lang, $modelName, $offset, $chunkSize)
	{
		return array();
	}
	
	/**
	 * @param website_persistentdocument_website $document
	 * @return website_MenuEntry|null
	 */
	public function getMenuEntry($document)
	{
		return $this->doGetMenuEntry($document);
	}

	/**
	 * @param website_persistentdocument_website $document
	 * @return website_MenuEntry|null
	 */
	public function getSitemapEntry($document)
	{
		return $this->doGetMenuEntry($document);
	}

	/**
	 * @param website_persistentdocument_website $document
	 * @return website_MenuEntry|null
	 */
	protected function doGetMenuEntry($document)
	{
		$entry = website_MenuEntry::getNewInstance();
		$entry->setDocument($document);
		$entry->setLabel($document->getNavigationLabel());
		$entry->setUrl(LinkHelper::getDocumentUrl($document));
		$entry->setContainer(true);
		$entry->setInPath(true);
		if ($document->getIndexPage() && $document->getIndexPage()->getId() == website_WebsiteModuleService::getInstance()->getCurrentPageId())
		{
			$entry->setCurrent(true);
		}
		return $entry;
	}
	
	/**
	 * @param website_persistentdocument_website $document
	 * @return website_persistentdocument_menuitem[]
	 */
	public function getChildrenDocumentsForMenu($document)
	{
		return $document->getDocumentService()->getPublishedChildrenOf($document);
	}
	
	// Deprecated
	
	/**
	 * @deprecated (will be removed in 4.0) use website_ModuleService::inititalizeStructure()
	 */	
	public function initDefaultStruct($website, $scriptPath)
	{
		throw new Exception('Deprecated call to initDefaultStruct!');
	}
}
