<?php
class website_WebsiteModuleService extends BaseService
{
	/**
	 * @var website_WebsiteModuleService
	 */
	private static $instance;

	private $lang;
	private $ignoreVisibility = false;



    /**
     * Tableau des modeles de documents pouvant apparaitre dans un menu
     * @var array<string>
     */
    public static $modelNamesForMenu = array('modules_website/topic',
    	'modules_website/page', 'modules_website/pagegroup',
    	'modules_website/pageexternal', 'modules_website/pagereference');

	/**
	 * Returns the unique instance of website_WebsiteModuleService.
	 * @return website_WebsiteModuleService
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = new self();
		}
		return self::$instance;
	}

	// --- Configuration methods ---

	/**
	 * @param boolean $bool
	 * @return website_WebsiteModuleService
	 */
	public function ignoreVisibility($bool)
	{
		$this->ignoreVisibility = $bool;
		return $this;
	}

	/**
	 * @param string $lang
	 * @return website_WebsiteModuleService
	 */
	public function setLang($lang)
	{
		$this->lang = $lang;
		return $this;
	}


	/**
	 * @param f_persistentdocument_PersistentDocument $document
	 * @return Breadcrumb
	 */
	public function getBreadcrumb($document, $includeCurrentDocument = true)
	{
		$breadcrumb = new Breadcrumb();

		$documents = $document->getDocumentService()->getAncestorsOf($document);
		if (count($documents) == 0)
		{
			throw new TopicException('Could not find the tree node for document "'.$document->__toString().'".');
		}

		if ($includeCurrentDocument)
		{
			array_push($documents, $document);
		}

		$level = 0;
		foreach ($documents as $nodeDocument)
		{
			if (!$nodeDocument->isPublished())
			{
				continue;
			}

			if ($document instanceof website_persistentdocument_pageversion &&
				$nodeDocument instanceof website_persistentdocument_pagegroup)
			{
				continue;
			}

			// topic are visible in the breadcrumb if they are visible in the menus.
			if ($nodeDocument instanceof website_persistentdocument_topic
				&& ($this->ignoreVisibility || WebsiteHelper::isVisibleInMenu($nodeDocument)))
			{
				$breadcrumb->append($this->buildMenuItemFromDocument($nodeDocument, $level++));
			}
			elseif ($nodeDocument instanceof website_persistentdocument_website)
			{
				$breadcrumb->append($this->buildMenuItemFromDocument($nodeDocument, $level++));
			}
			elseif ($nodeDocument instanceof website_persistentdocument_page && !$nodeDocument->getIsIndexPage())
			{
				$breadcrumb->append($this->buildMenuItemFromDocument($nodeDocument, $level++));
			}
		}
		return $breadcrumb;
	}

	/**
	 * @param f_persistentdocument_PersistentDocument $pageRef
	 * @return string
	 */
	public static function getJsPagePath($pageRef)
	{
		return $this->getBreadcrumb($pageRef)->renderAsJavascript();
	}


	/**
	 * Returns the Sitemap object with all the entries needed to build the sitemap.
	 *
	 * @return Sitemap
	 *
	 * @throws BaseException("invalid_lang");
	 */
	public final function getSitemap($website = null, $maxLevel = 5)
	{
		if (is_null($website))
		{
			$website = website_WebsiteService::getInstance()->getDefaultWebsite();
		}
		$sitemap = new Sitemap();
		$sitemap->setMaxLevel($maxLevel);
		$treeNode = TreeService::getInstance()->getInstanceByDocument($website);
		if ($maxLevel > 0 || $maxLevel == -1)
		{
			TreeService::getInstance()->loadDescendants($treeNode, $maxLevel);
		}

		$this->populateSitemapFromDescendants($sitemap, $treeNode, 0, $maxLevel);
		return $sitemap;
	}


	/**
	 * @param string $shortTagName The short tag name, without 'contextual_website_website_'.
	 * @param integer $maxLevel
	 * @return Menu
	 */
	public final function getMenuByTag($shortTagName, $maxLevel = -1)
	{
		$menuDoc = $this->getMenuDocumentByTag($shortTagName);
		return $this->getMenu($menuDoc, $maxLevel);
	}

    /**
     * @param integer $id the menu item id
     * @return array<Menu>
     */
    public final function getMenusByMenuItemId($id)
    {
        $ancestorsIds = DocumentHelper::getIdArrayFromDocumentArray(TreeService::getInstance()->getInstanceByDocumentId($id)->getAncestors());
        $ancestorsIds[] = $id;
        $menuObjects = array();
        $menuItemDocuments = $this->getPersistentProvider()->createQuery('modules_website/menuitemdocument')->add(Restrictions::in('document.id', $ancestorsIds))->find();
		foreach ($menuItemDocuments as $menuItemDocument)
		{
			$menuArray = $menuItemDocument->getMenuArrayInverse();
			$menuObjects[$menuArray[0]->getId()] = $menuArray[0];
		}
        return array_values($menuObjects);
    }

    /**
     * @param integer $id the menu item id
     * @return Menu or null if no menu contains the document
     */
    public final function getMenuByMenuItemId($id)
    {
        $menuObjects = $this->getMenusByMenuItemId($id);
        if (!empty($menuObjects))
        {
            return $menuObjects[0];
        }
        return null;
    }

    /**
     * @param string $shortTagName The short tag name, without 'contextual_website_website_'.
     * @param integer $maxLevel
	 * @param integer $topEntriesLevel Number of top-level entries to display when they are collapsed.
     * @return Menu
     */
    public final function getRestrictedMenuByTag($shortTagName, $maxLevel = -1, $topEntriesLevel = 1)
    {
        $menuDoc = $this->getMenuDocumentByTag($shortTagName);
        return $this->getRestrictedMenu($menuDoc, $maxLevel, $topEntriesLevel);
    }

    private function getMenuDocumentByTag($shortTagName)
    {
        $tagName = 'contextual_website_website_' . $shortTagName;
        $menuDoc = TagService::getInstance()->getDocumentByContextualTag($tagName, website_WebsiteService::getInstance()->getCurrentWebsite(), false);
        if ($menuDoc === null)
        {
            throw new TopicException('No menu has the tag "'.$tagName.'".');
        }
        return $menuDoc;
    }


	/**
	 * @param website_persistentdocument_menu $menuDocument
	 * @param integer $maxLevel
	 * @return Menu
	 */
	public function getMenu($menuDocument, $maxLevel = -1)
	{
		$menuObject = new Menu();
		$menuObject->setMaxLevel($maxLevel);
		foreach ($menuDocument->getMenuItemArray() as $menuItemDocument)
		{
			if (WebsiteHelper::isVisibleInMenu($menuItemDocument))
			{
				$count = $menuObject->count();
				$menuItem = $this->buildMenuItemFromDocument($menuItemDocument, 0);
				$menuObject[$count] = $menuItem;
				if ($menuItemDocument instanceof website_persistentdocument_menuitemdocument)
				{
					$item = $menuItemDocument->getDocument();
					if ($item instanceof website_persistentdocument_topic)
					{
						if ($maxLevel > 0 || $maxLevel == -1)
						{
							$node = TreeService::getInstance()->getInstanceByDocument($item);
							TreeService::getInstance()->loadDescendants($node, $maxLevel);
							$subItemsCount = $this->populateNavigationElementFromDescendants($menuObject, $node, 1, $maxLevel);
							if (!$subItemsCount && !$menuItem->hasUrl())
							{
								$menuObject->offsetUnset($count);
							}
						}
					}
				}
			}
		}
		return $menuObject;
	}

	/**
	 * Builds and returns a restricted menu.
	 * A restricted menu is a menu that contains all the top level topics,
	 * collapsed. Only the ancestors topics (and sub-topics) of the current page
	 * are expanded.
	 *
	 * @param website_persistentdocument_menu $menuDocument
	 * @param integer $maxLevel
	 * @param integer $topEntriesLevel Number of top-level entries to display when they are collapsed.
	 * @return Menu
	 */
	public function getRestrictedMenu($menuDocument, $maxLevel = -1, $topEntriesLevel = 1)
	{
		$menuObject = new Menu();
		$menuObject->setMaxLevel($maxLevel);
		foreach ($menuDocument->getMenuItemArray() as $menuItemDocument)
		{
			if (WebsiteHelper::isVisibleInMenu($menuItemDocument))
			{
				$menuItem = $this->buildMenuItemFromDocument($menuItemDocument, 0);
				$menuObject->append($menuItem);
				if ($menuItemDocument instanceof website_persistentdocument_menuitemdocument)
				{
					$item = $menuItemDocument->getDocument();
					if ($item instanceof website_persistentdocument_topic)
					{
						if (($maxLevel > 0 || $maxLevel == - 1) && (in_array($item->getId(), website_PageService::getInstance()->getCurrentPageAncestorsIds())))
						{
							$node = TreeService::getInstance()->getInstanceByDocument($item);
							TreeService::getInstance()->loadDescendants($node, $maxLevel);
							$this->populateNavigationElementFromCurrentDescendants($menuObject, $node, 1, $maxLevel);
						}
						else if ($topEntriesLevel > 1)
						{
							$node = TreeService::getInstance()->getInstanceByDocument($item);
							TreeService::getInstance()->loadDescendants($node, $topEntriesLevel);
							$this->populateNavigationElementFromDescendants($menuObject, $node, 1, $topEntriesLevel-1);
						}
					}
				}
			}
		}

		return $menuObject;
	}


	/**
	 * Builds and returns a menu that contains all the siblings of $fromDocument
	 * and their descendents.
	 *
	 * @param f_persistentdocument_PersistentDocument $fromDocument
	 * @param integer $maxLevel
	 * @return Menu
	 */
	public final function getContextMenu($fromDocument = null, $maxLevel = -1)
	{
		return $this->getMenuByTopic($this->getParentNodeFromDocument($fromDocument), $maxLevel);
	}


	/**
	 * @param f_persistentdocument_PersistentDocument $fromDocument
	 * @param integer $maxLevel
	 * @return Menu
	 */
	public final function getRestrictedContextMenu($fromDocument = null, $maxLevel = -1)
	{
		return $this->getRestrictedMenuByTopic($this->getParentNodeFromDocument($fromDocument), $maxLevel);
	}


	/**
	 * @param website_persistentdocument_topic $topic
	 * @param integer $maxLevel
	 * @return Menu
	 */
	public final function getRestrictedMenuByTopic($topic, $maxLevel = -1)
	{
		$menuObject = new Menu();
		$menuObject->setMaxLevel($maxLevel);
		$node = TreeService::getInstance()->getInstanceByDocument($topic);
		if ($maxLevel > 0 || $maxLevel == -1)
		{
			TreeService::getInstance()->loadDescendants($node, $maxLevel);
		}
		$this->populateNavigationElementFromCurrentDescendants($menuObject, $node, 0, $maxLevel);
		return $menuObject;
	}


	/**
	 * @param website_persistentdocument_topic $topic
	 * @param integer $maxLevel
	 * @return Menu
	 */
	public final function getMenuByTopic($topic, $maxLevel = -1)
	{
		$menuObject = new Menu();
		$menuObject->setMaxLevel($maxLevel);
		$node = TreeService::getInstance()->getInstanceByDocument($topic);
		if ($maxLevel > 0 || $maxLevel == -1)
		{
		    TreeService::getInstance()->loadDescendants($node, $maxLevel);
		}
		$this->populateNavigationElementFromDescendants($menuObject, $node, 0, $maxLevel);
		return $menuObject;
	}


	/**
	 * @param f_persistentdocument_PersistentDocument $document
	 * @return f_persistentdocument_PersistentTreeNode
	 */
	public final function getParentNodeFromDocument($document)
	{
		$document = DocumentHelper::getByCorrection($document);

		if ($document instanceof website_persistentdocument_pageversion)
		{
			$parentNode = TreeService::getInstance()->getInstanceByDocument($document)->getParent()->getParent();
		}
		else if ($document === null)
		{
			$parentNode = TreeService::getInstance()->getInstanceByDocument(website_WebsiteService::getInstance()->getCurrentWebsite());
		}
		else
		{
			$parentNode = TreeService::getInstance()->getInstanceByDocument($document)->getParent();
		}
		return $parentNode;
	}

	/**
	 * @param NavigationElementImpl $navigationElement
	 * @param f_persistentdocument_PersistentTreeNode $parentNode
	 * @param integer $level
	 * @param integer $maxLevel
	 * @return integer
	 */
	private function populateNavigationElementFromDescendants(NavigationElementImpl $navigationElement, f_persistentdocument_PersistentTreeNode $parentNode, $level, $maxLevel)
	{
		$count = $navigationElement->count();
		foreach ($parentNode->getChildren() as $child)
		{
			$doc = $child->getPersistentDocument();
			if (WebsiteHelper::isVisibleInMenu($doc))
			{
				$menuItem = $this->buildMenuItemFromDocument($doc, $level);
				$navigationElement[$navigationElement->count()] = $menuItem;
				if (($level < $maxLevel || $maxLevel == -1) && $doc instanceof website_persistentdocument_topic)
				{
					$this->populateNavigationElementFromDescendants($navigationElement, $child, $level+1, $maxLevel);
				}
			}
		}
		return $navigationElement->count() - $count;
	}


	/**
	 * @param NavigationElementImpl $navigationElement
	 * @param f_persistentdocument_PersistentTreeNode $parentNode
	 * @param integer $level
	 * @param integer $maxLevel
	 * @return integer
	 */
	private function populateNavigationElementFromCurrentDescendants(NavigationElementImpl $navigationElement, f_persistentdocument_PersistentTreeNode $parentNode, $level, $maxLevel)
	{
		$count = $navigationElement->count();
		foreach ($parentNode->getChildren() as $child)
		{
			$doc = $child->getPersistentDocument();
			if (WebsiteHelper::isVisibleInMenu($doc))
			{
				$menuItem = $this->buildMenuItemFromDocument($doc, $level);
				$navigationElement[$navigationElement->count()] = $menuItem;
				if (($level < $maxLevel || $maxLevel == -1) && $doc instanceof website_persistentdocument_topic)
				{
					if (in_array($doc->getId(), website_PageService::getInstance()->getCurrentPageAncestorsIds()))
					{
						$this->populateNavigationElementFromCurrentDescendants($navigationElement, $child, $level+1, $maxLevel);
					}
				}
			}
		}
		return $navigationElement->count() - $count;
	}





	/**
	 * @param Sitemap $navigationElement
	 * @param f_persistentdocument_PersistentTreeNode $parentNode
	 * @param integer $level
	 * @param integer $maxLevel
	 */
	private function populateSitemapFromDescendants(Sitemap $navigationElement, f_persistentdocument_PersistentTreeNode $parentNode, $level, $maxLevel)
	{
		foreach ($parentNode->getChildren() as $child)
		{
			$doc = $child->getPersistentDocument();
			if ($doc instanceof website_persistentdocument_menufolder || !$doc->isPublished())
			{
				continue;
			}
			if (WebsiteHelper::isVisibleInSitemap($doc) )
			{
				$navigationElement->append($this->buildMenuItemFromDocument($doc, $level));
				if (($level < $maxLevel || $maxLevel == -1) && $doc instanceof website_persistentdocument_topic)
				{
					$this->populateSitemapFromDescendants($navigationElement, $child, $level+1, $maxLevel);
				}
			}
		}
	}


	/**
	 * @param f_persistentdocument_PersistentDocument $document
	 * @return string
	 */
	public static function getNavigationTitleFor($document)
	{
		$label = null;
		if ($document instanceof website_PublishableElement)
		{
			$label = f_util_HtmlUtils::textToHtml($document->getNavigationtitle());
		}
		if (empty($label))
		{
			$label = $document->getLabelAsHtml();
		}
		if (f_Locale::isLocaleKey($label))
		{
			$label = f_Locale::translate($label);
		}
		return $label;
	}

	/**
	 * @param f_persistentdocument_PersistentDocument $document
	 * @param Integer $level
	 * @return website_MenuItem
	 */
	private function buildMenuItemFromDocument($document, $level = 0)
	{
		$entry = new website_MenuItem();

		if ($document instanceof website_persistentdocument_menuitemdocument)
		{
			$entry->setPopup($document->getPopup());
			$entry->setPopupParameters($document->getPopupParameters());
			$document = $document->getDocument();
		}

		$this->buildMenuItemUrlFromDocument($entry, $document);
		$this->buildMenuItemLabelFromDocument($entry, $document);

		$entry->setId($document->getId());
		$entry->setType(($document instanceof website_persistentdocument_topic) ? website_MenuItem::TYPE_TOPIC : website_MenuItem::TYPE_PAGE);
		$entry->setDocumentModelName($document->getDocumentModelName());
		$entry->setLevel($level);
		if (method_exists($document, 'getDescription'))
		{
			$entry->setDescription($document->getDescription());
		}
		if (method_exists($document, 'getNavigationVisibility'))
		{
			$entry->setNavigationVisibility($document->getNavigationVisibility());
		}
		return $entry;
	}

	/**
	 * @param website_MenuItem $entry
	 * @param f_persistentdocument_PersistentDocument $document
	 * @return String
	 */
	protected function buildMenuItemLabelFromDocument($entry, $document)
	{
		$label = null;
		if ($document instanceof website_persistentdocument_website)
		{
			$label = f_Locale::translate('&modules.website.frontoffice.thread.Homepage-href-name;');
		}
		else if (!$document instanceof website_persistentdocument_menuitemtext)
		{
			$label = self::getNavigationTitleFor($document);
		}
		else if ($document instanceof website_persistentdocument_menuitemtext)
		{
			$label = $document->getLabelAsHtml();
		}
		$entry->setLabel($label);
	}

	/**
	 * @param website_MenuItem $entry
	 * @param f_persistentdocument_PersistentDocument $document
	 * @return void
	 */
	protected function buildMenuItemUrlFromDocument($entry, $document)
	{
		if (!$document instanceof website_persistentdocument_menuitemtext)
		{
			if ($document instanceof website_persistentdocument_menuitemfunction)
			{
				$this->updateMenuitemFromMenuitemfunction($entry, $document);
			}
			else if ($document instanceof website_persistentdocument_topic)
			{
				if ($document->hasPublishedIndexPage())
				{
					$entry->setUrl(LinkHelper::getDocumentUrl($document));
				}
			}
			else
			{
				$entry->setUrl(LinkHelper::getDocumentUrl($document));
			}
		}
	}

	/**
	 * @param website_MenuItem $menuitem
	 * @param website_persistentdocument_menuitemfunction $menuitemfunction
	 */
	private function updateMenuitemFromMenuitemfunction($menuitem, $menuitemfunction)
	{
		// OK, I admit this is not very clean, but... I don't think this will
		// evolve anymore.
		$url = $menuitemfunction->getUrl();
		if (f_util_StringUtils::beginsWith($url, "function:"))
		{
			$menuFunctionClass = 'website_MenuItem'.ucfirst(substr($url, 9))."Function";
			if (f_util_ClassUtils::classExists($menuFunctionClass))
			{
				f_util_ClassUtils::callMethodArgs($menuFunctionClass, "execute", array($menuitem));
				return;
			}
		}
		$menuitem->setUrl($url);
	}

	private static $_systemStylesheets = array('backoffice', 'print', 'bindings', 'frontoffice', 'richtext');

	public function getWebsiteAndTopicStylesheets()
	{
		$availablePaths = FileResolver::getInstance()
            ->setPackageName('modules_website')
            ->setDirectory('style')
            ->getPaths('');

        $styles = array();

        foreach ($availablePaths as $availablePath)
        {
            if (is_dir($availablePath))
            {
            	$dh = opendir($availablePath);
                if ($dh)
                {
                    while (($file = readdir($dh)) !== false)
                    {
                    	$fileMatch = array();
                        if (preg_match('/^((?:website|topic)[a-zA-Z0-9_-]+)\.css$/', $file, $fileMatch))
            			{
            			    $fileName = $fileMatch[1];
            			    if (!in_array($fileName, self::$_systemStylesheets))
            			    {
            			    	$styles[$fileName] = f_Locale::translateUI('&modules.website.bo.styles.' . $fileName . ';');
            			    }
            			}
                    }
                    closedir($dh);
                }
            }
        }
		return $styles;
	}
	
	// DEPRECATED

    /**
     * @deprecated use LinkHelper::getEmptyUrl()
     */
    const EMPTY_URL = '#';	
    
    public function __call($name, $arguments)
	{
		switch ($name)
		{
			case 'getEmptyUrl': 
				Framework::error('Call to deleted ' . get_class($this) . '->' . $name . ' method');		
				return LinkHelper::getEmptyUrl();
					
			case 'setCurrentPageId': 
				Framework::error('Call to deleted ' . get_class($this) . '->' . $name . ' method');	
				return website_PageService::getInstance()->setCurrentPageId($arguments[0]);	
			case 'getCurrentPageId': 
				Framework::error('Call to deleted ' . get_class($this) . '->' . $name . ' method');	
				return website_PageService::getInstance()->getCurrentPageId();
			case 'getCurrentPage': 
				Framework::error('Call to deleted ' . get_class($this) . '->' . $name . ' method');	
				return website_PageService::getInstance()->getCurrentPage();
			case 'getCurrentPageAncestorsIds': 
				Framework::error('Call to deleted ' . get_class($this) . '->' . $name . ' method');	
				return website_PageService::getInstance()->getCurrentPageAncestorsIds();
			case 'getCurrentPageAncestors': 
				Framework::error('Call to deleted ' . get_class($this) . '->' . $name . ' method');	
				return website_PageService::getInstance()->getCurrentPageAncestors();				
			case 'getIndexPage': 
				Framework::error('Call to deleted ' . get_class($this) . '->' . $name . ' method');
				$topic = $arguments[0]; $getFirstPageIfNotFound = isset($arguments[1]) ? $arguments[1] : true;	
				if ($topic instanceof website_persistentdocument_topic || $topic instanceof website_persistentdocument_website)
				{
					return $topic->getDocumentService()->getIndexPage($topic, $getFirstPageIfNotFound);
				}
				return null;
			case 'removeIndexPage': 
				Framework::error('Call to deleted ' . get_class($this) . '->' . $name . ' method');
				$topicOrPage = $arguments[0]; $userSetting = isset($arguments[1]) ? $arguments[1] : false;	
				if ($topicOrPage instanceof website_persistentdocument_topic)
		        {
		           	$topicOrPage->getDocumentService()->removeIndexPage($topicOrPage, $userSetting);
		        }
		       	elseif ($topicOrPage instanceof website_persistentdocument_page)
		        {
		        	$topicOrPage->getDocumentService()->removeIndexPage($topicOrPage, $userSetting);
		        }
		        elseif ($topicOrPage instanceof website_persistentdocument_pageexternal)
		        {
		        	$topicOrPage->getDocumentService()->removeIndexPage($topicOrPage, $userSetting);
		        }
				return;
			case 'setHomePage': 
				Framework::error('Call to deleted ' . get_class($this) . '->' . $name . ' method');	
				if ($arguments[0] instanceof website_persistentdocument_page)
				{
					$arguments[0]->getDocumentService()->makeHomePage($arguments[0]);
				}
				return;
			case 'setIndexPage': 
				Framework::error('Call to deleted ' . get_class($this) . '->' . $name . ' method');
				if ($arguments[0] instanceof website_persistentdocument_page)
				{
					$arguments[0]->getDocumentService()->makeIndexPage($arguments[0], $userSetting);
				}
				return;

			case 'setWebsiteMetaFromParentId': 
				Framework::error('Call to deleted ' . get_class($this) . '->' . $name . ' method');	
				return website_WebsiteService::getInstance()->setWebsiteMetaFromParentId($arguments[0], $arguments[1]);					
			case 'hasUniqueDomainNameForLang': 
				Framework::error('Call to deleted ' . get_class($this) . '->' . $name . ' method');	
				return website_WebsiteService::getInstance()->hasUniqueDomainNameForLang($arguments[0], $arguments[1]);	
			case 'getDefaultWebsite': 
				Framework::error('Call to deleted ' . get_class($this) . '->' . $name . ' method');
				return website_WebsiteService::getInstance()->getDefaultWebsite();				
			case 'setDefaultWebsite': 
				Framework::error('Call to deleted ' . get_class($this) . '->' . $name . ' method');
				return website_WebsiteService::getInstance()->setDefaultWebsite($arguments[0]);				 
			case 'getWebsiteByUrl': 
				Framework::error('Call to deleted ' . get_class($this) . '->' . $name . ' method');
				return website_WebsiteService::getInstance()->getByUrl($arguments[0], isset($arguments[1]) ? $arguments[1] : false);
			case 'getCurrentWebsite': 
				Framework::error('Call to deleted ' . get_class($this) . '->' . $name . ' method');
				return website_WebsiteService::getInstance()->getCurrentWebsite(isset($arguments[0]) ? $arguments[0] : false);				
			case 'setCurrentWebsiteId': 
				Framework::error('Call to deleted ' . get_class($this) . '->' . $name . ' method');
				return website_WebsiteService::getInstance()->setCurrentWebsiteId($arguments[0]);
			case 'setCurrentWebsite': 
				Framework::error('Call to deleted ' . get_class($this) . '->' . $name . ' method');
				return website_WebsiteService::getInstance()->setCurrentWebsite($arguments[0]);
			case 'getWebsiteInfos': 
				Framework::error('Call to deleted ' . get_class($this) . '->' . $name . ' method');
				return website_WebsiteService::getInstance()->getWebsiteInfos($arguments[0]);
			case 'getParentWebsite': 
				Framework::error('Call to deleted ' . get_class($this) . '->' . $name . ' method');
				return website_WebsiteService::getInstance()->getByDocument($arguments[0]);								
			default:
				throw new Exception('No method ' . get_class($this) . '->' . $name);				
		}
	}
}