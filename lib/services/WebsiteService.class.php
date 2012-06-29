<?php
/**
 * @package modules.website
 * @method website_WebsiteService getInstance()
 */
class website_WebsiteService extends f_persistentdocument_DocumentService
{
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
		return $this->getPersistentProvider()->createQuery('modules_website/website');
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
	 * @param integer $parentNodeId Parent node ID where to save the document (optionnal).
	 * @return void
	 */
	protected function preSave($document, $parentNodeId)
	{	 
		$document->setProtocol('http');
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
		
		$group = $document->getGroup();
		if ($group === null)
		{
			$group = $this->createAuthenticationGroup($document);
		}
	}
	
	/**
	 * @param website_persistentdocument_website $document
	 */
	protected function createAuthenticationGroup($document)
	{
		$grp = users_GroupService::getInstance()->getNewDocumentInstance();
		$grp->setLabel($document->getLabel());
		$grp->save(ModuleService::getInstance()->getRootFolderId('users'));
		$document->setGroup($grp);
		return $grp;
	}
	
	/**
	 * @param website_persistentdocument_website $document
	 * @param integer $parentNodeId Parent node ID where to save the document (optionnal).
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
			$default = f_util_FileUtils::read(f_util_FileUtils::buildProjectPath('media', 'frontoffice', 'robots.txt'));
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
	 * @param integer $parentNodeId Parent node ID where to save the document (optionnal).
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
	 * @param integer $parentNodeId Parent node ID where to save the document (optionnal).
	 * @return void
	 */
	protected function postInsert($document, $parentNodeId)
	{
		// Replace linked-to-root-module document model attribute.
		if ($document->getTreeId() === null)
		{
			TreeService::getInstance()->newLastChild(ModuleService::getInstance()->getRootFolderId('website'), $document->getId());
		}
		
		// If we are creating the first website document, set it as the default website.
		$query = $this->createQuery()->add(Restrictions::hasTag('default_modules_website_default-website'));
		if ($query->findUnique() === null)
		{	
			$this->setDefaultWebsite($document);
		}

		// Create the menus folder where the website's menus will be stored.
		$menuFolder = website_MenufolderService::getInstance()->getNewDocumentInstance();
		$menuFolder->save($document->getId());

		$initScript = $document->getStructureinit();
		if (f_util_StringUtils::isNotEmpty($initScript) && $document->getTemplate())
		{
			$this->generateDefaultStructure($document, $initScript);
		}
		$roleName = 'modules_website.AuthenticatedFrontUser';
		change_PermissionService::getInstance()->addRoleToGroup($document->getGroup(), $roleName, array($document->getId()));
		
		$anonymouseUser = users_AnonymoususerService::getInstance()->getAnonymousUser();
		change_PermissionService::getInstance()->addRoleToUser($anonymouseUser, $roleName, array($document->getId()));	
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
	 * Indicates whether the given $website has a unique URL for its version in
	 * language $lang.
	 *
	 * @param website_persistentdocument_website $website
	 * @param string $lang
	 *
	 * @return boolean
	 */
	public function hasUniqueDomainNameForLang($website, $lang)
	{
		$urlForLang = $website->getUrlForLang($lang);
		$i18nWebsites = $this->getPersistentProvider()->getI18nWebsitesFromUrl($urlForLang);
		return count($i18nWebsites) == 1;
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
			$this->getTransactionManager()->beginTransaction();
			
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
			$this->getTransactionManager()->commit();
		}
		catch (Exception $e)
		{
			$this->getTransactionManager()->rollBack($e);
		}		  
	}
	
	/**
	 * Returns the index page for a website.
	 *
	 * @param website_persistentdocument_website $website
	 * @param boolean $getFirstPageIfNotFound If true, and if no index page is defined, get the first child page.
	 *
	 * @return website_persistentdocument_page || null
	 */
	public function getIndexPage($website, $getFirstPageIfNotFound = false)
	{
		$indexPage = $website->getIndexPage();
		if ($indexPage === null && $getFirstPageIfNotFound)
		{
			return website_PageService::getInstance()->getFirstPublished($website);
		}
		return $indexPage;
	}
	
	/**
	 * Returns the index page for a website.
	 *
	 * @param website_persistentdocument_website $website
	 * @param boolean $getFirstPageIfNotFound If true, and if no index page is defined, get the first child page.
	 *
	 * @return website_persistentdocument_page || null
	 */
	public function getHomePage($website, $getFirstPageIfNotFound = false)
	{
		return $this->getIndexPage($website, $getFirstPageIfNotFound);
	}
	
	/**
	 * @return Array<website_persistentdocument_website>
	 */
	public function getAll()
	{
		return $this->createQuery()->find();
	}
	
	/**
	 * @param integer $descendentId
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
	 * @var website_persistentdocument_website
	 */
	private $currentWebsite = null;

	/**
	 * @var website_persistentdocument_website
	 */
	private $defaultWebsite = null;

	
	/**
	 * Returns the default website for the whole project.
	 *
	 * If a website is exclusively tagged with 'default_modules_website_default-website',
	 * this website will be returned. Otherwise, this method returns the first website it
	 * finds in the website module, <strong>using an undefined order</strong>.
	 *
	 * @return website_persistentdocument_website
	 */
	public function getDefaultWebsite()
	{
		if ($this->defaultWebsite === null)
		{
			try
			{
				$this->defaultWebsite = TagService::getInstance()->getDocumentByExclusiveTag('default_modules_website_default-website');
			}
			catch (TagException $e)
			{
				if (Framework::isDebugEnabled())
				{
					Framework::exception($e);
				}

				$this->defaultWebsite = $this->getNewDocumentInstance();
				$this->defaultWebsite->setLabel('Temporary web site');
				$this->defaultWebsite->setDomain(Framework::getUIDefaultHost());
				$protocol = RequestContext::getInstance()->getProtocol();
				$this->defaultWebsite->setProtocol($protocol);
				$this->defaultWebsite->setUrl($protocol . '://'. Framework::getUIDefaultHost());
			}
		}
		return $this->defaultWebsite;
	}
	
	/**
	 * @param website_persistentdocument_website $website
	 */
	public function setDefaultWebsite($website)
	{
		TagService::getInstance()->setExclusiveTag($website, 'default_modules_website_default-website');
		$this->defaultWebsite = $website;
	}
	
	/**
	 * @param boolean $setLang try to set the context language
	 * @return website_persistentdocument_website
	 */
	public function getCurrentWebsite($setLang = false)
	{
		if ($this->currentWebsite === null)
		{
			$currentWebsite = null;

			if (isset($_SERVER['HTTP_HOST']))
			{
				$host = $_SERVER['HTTP_HOST'];
				$currentWebsite = $this->getByUrl($host, $setLang);
			}

			if ($currentWebsite === null)
			{
				$currentWebsite = $this->getDefaultWebsite();
				if ($setLang)
				{
					RequestContext::getInstance()->setLang($currentWebsite->getLang());
				}
			}

			$this->setCurrentWebsite($currentWebsite);
		}

		return $this->currentWebsite;
	}
	
	/**
	 * @param integer $websiteId
	 * @return website_persistentdocument_website
	 */
	public function setCurrentWebsiteId($websiteId)
	{
		$this->setCurrentWebsite($this->getDocumentInstance($websiteId, 'modules_website/website'));
		return $this->currentWebsite;
	}
	
	/**
	 * @param website_persistentdocument_website $website
	 */
	public function setCurrentWebsite($website)
	{
		if (RequestContext::getInstance()->inHTTPS())
		{
			$website->setProtocol('https');
		}
		
		$cu = users_UserService::getInstance()->getCurrentUser();
		if ($cu === null)
		{
			users_GroupService::getInstance()->setDefaultGroup($website->getGroup());
			users_ProfileService::getInstance()->initCurrent();
		}
		
		$this->currentWebsite = $website;
	}
	
	/**
	 * @param string $domaine
	 * @param boolean $setLang
	 * @return website_persistentdocument_website
	 */
	public function getByUrl($domaine, $setLang = false)
	{
		$domaines = $this->getWebsitesDomain();
		if (isset($domaines[$domaine]))
		{
			$data = $domaines[$domaine];
			if ($setLang)
			{
				RequestContext::getInstance()->setLang($data['langs'][0]);
			}

			return $this->getDocumentInstance($data['id'], "modules_website/website");
		}
		return null;
	}
	
	/**
	 * @param string $domaine
	 * @return array<id=>integer, localizebypath=>boolean, langs=>array<lang>>
	 */
	public function getWebsiteInfos($domaine)
	{
		$domaines = $this->getWebsitesDomain();
		if (isset($domaines[$domaine]))
		{
			return $domaines[$domaine];
		}

		return null;
	}
	
	/**
	 * @return array<>
	 */
	private function getWebsitesDomain()
	{
			$isCacheEnabled = (f_DataCacheService::getInstance()->isEnabled());
			if ($isCacheEnabled)
			{
				$simpleCache = f_DataCacheService::getInstance();
				$cacheItem = $simpleCache->readFromCache(__CLASS__, array('domaines'), array('modules_website/website'));
				
				if ($cacheItem !== null && $cacheItem->isValid())
				{
					return unserialize($cacheItem->getValue('sites'));
				}
			}

			$domaines = $this->compileWebsitesDomain();

			if ($isCacheEnabled)
			{
				$cacheItem->setValue('sites', serialize($domaines));
				$simpleCache->writeToCache($cacheItem);
			}

			return $domaines;
	}

	private function compileWebsitesDomain()
	{
		$rc = RequestContext::getInstance();
		$domaines = array();

		$websites = $this->getAll();

		$supportedLanguages = $rc->getSupportedLanguages();
		foreach ($websites as $website)
		{
			$localizebypath = $website->getLocalizebypath();
			$domaineInfo = array('id' => $website->getId(), 'localizebypath' => $localizebypath, 'langs' => array());
			foreach ($supportedLanguages as $supportedLanguage)
			{
				if ($website->isLangAvailable($supportedLanguage))
				{
				   $domaine = $website->getDomainForLang($supportedLanguage);
				   if (!isset($domaines[$domaine]))
				   {
				   		$domaines[$domaine] = $domaineInfo;
				   }
				   $domaines[$domaine]['langs'][] = $supportedLanguage;
				}
			}
		}
		return $domaines;
	}
	
	/**
	 * Returns the parent website document for $document or null if no website
	 * document is a parent of $document.
	 *
	 * @param f_persistentdocument_PersistentDocument $document
	 * @return website_persistentdocument_website
	 */
	public function getByDocument($document)
	{
		$websiteId = $document->getDocumentService()->getWebsiteId($document);
		if ($websiteId)
		{
			return DocumentHelper::getDocumentInstance($websiteId, 'modules_website/website');
		}
		return null;
	}
	
	/**
	 * Set the meta websiteId on the given document, using the parent document one.
	 * Warning: the document has to be persisted.
	 * @param f_persistentdocument_PersistentDocument $document
	 * @param integer $parentId
	 */
	public function setWebsiteMetaFromParentId($document, $parentId)
	{
		if ($parentId !== null)
		{
			$parent = $this->getDocumentInstance($parentId);
			if ($parent instanceof website_persistentdocument_website)
			{
				$website = $parent;
			}
			else
			{
				$website = $this->getByDocument($parent);
			}
			if ($website)
			{
				$document->setMeta("websiteId", $website->getId());
			}
			else
			{
				$document->setMeta("websiteId", null);
			}
		}
		else
		{
			$document->setMeta("websiteId", null);
		}
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
}