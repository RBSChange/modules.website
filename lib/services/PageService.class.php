<?php
/**
 * @package modules.website
 * @method website_PageService getInstance()
 */
class website_PageService extends f_persistentdocument_DocumentService
{
	/**
	 * @param string $templateName
	 * @return website_persistentdocument_page[]
	 */
	function getByTemplate($templateName)
	{
		return $this->createQuery()->add(Restrictions::eq("template", $templateName))->find();
	}
	
	/**
	 * @return website_persistentdocument_page
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_website/page');
	}
	
	/**
	 * Create a query based on 'modules_modules_website/page' model
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->getPersistentProvider()->createQuery('modules_website/page');
	}
	
	/**
	 * @param website_persistentdocument_page $page
	 */
	protected function synchronizeReferences($page)
	{
		
		if ($page instanceof website_persistentdocument_page)
		{
			$query = $this->getPersistentProvider()->createQuery('modules_website/pagereference')->add(Restrictions::eq('referenceofid', $page->getId()));
			$pagesReference = $query->find();
			
			$copyToVo = $page->getLang() == RequestContext::getInstance()->getLang();
			
			foreach ($pagesReference as $pageReference)
			{
				$isIndex = $pageReference->getIsIndexPage();
				$isHome = $pageReference->getIsHomePage();
				
				$page->copyPropertiesTo($pageReference, $copyToVo);
				
				$pageReference->setIsIndexPage($isIndex);
				$pageReference->setIsHomePage($isHome);
				
				$pageReference->save();
			}
		}
	}
	
	/**
	 * @param website_persistentdocument_page $document
	 * @param string $oldPublicationStatus
	 * @param array $params
	 * @return void
	 */
	protected function publicationStatusChanged($document, $oldPublicationStatus, $params)
	{
		$parentDocument = TreeService::getInstance()->getParentDocument($document);
		if ($parentDocument instanceof website_persistentdocument_topic)
		{
			if ($parentDocument->isPublished() != $document->isPublished())
			{
				website_TopicService::getInstance()->publishDocumentIfPossible($parentDocument, array(
					'childrenPublicationStatusChanged' => $document));
			}
		}
		
		if ("CORRECTION" == $oldPublicationStatus && isset($params["cause"]) && "activate" == $params["cause"])
		{
			$correction = DocumentHelper::getDocumentInstance($params["correctionId"]);
			$oldDom = $this->getDomFromPageContent($correction);
			$newDom = $this->getDomFromPageContent($document);
			$this->doBlockCallbacks($document, $oldDom, $newDom);
		}
		$this->synchronizeReferences($document);
	}
	
	/**
	 * @param website_persistentdocument_page $document
	 * @param integer $parentNodeId
	 */
	protected function preInsert($document, $parentNodeId = null)
	{
		if ($document->getMetatitle() === null)
		{
			$document->setMetatitle('{page.label}');
		}
		
		if ($document->getContent() === null)
		{
			$this->initContent($document);
		}
		website_WebsiteService::getInstance()->setWebsiteMetaFromParentId($document, $parentNodeId);
	}
	
	/**
	 * @param website_persistentdocument_page $document
	 * @param integer $parentNodeId
	 */
	protected function preUpdate($document, $parentNodeId)
	{
		if ($document->getContent() === null)
		{
			$fromLang = $document->getFromlang();
			if ($fromLang !== null && $document->isLangAvailable($fromLang))
			{
				$document->setContent($document->getContentForLang($fromLang));
			}
			else
			{
				$document->setContent($document->getVoContent());
			}
		}
	}
	
	/**
	 * @param website_persistentdocument_page $document
	 * @param integer $parentNodeId
	 */
	protected function preSave($document, $parentNodeId)
	{
		$this->buildBlockMetaInfo($document);
	}
	
	/**
	 * @param website_persistentdocument_page $document
	 */
	public function getDisplayPage($document)
	{
		return DocumentHelper::getByCorrection($document);
	}
	
	/**
	 * @param website_persistentdocument_page $document
	 * @return string|null
	 */
	public function getNavigationLabel($document)
	{
		$nl = $document->getNavigationtitle();
		return ($nl) ? $nl : parent::getNavigationLabel($document);
	}
	
	/**
	 * Public for patch 304. Do not call ; private use.
	 * @param website_persistentdocument_page $document
	 */
	function buildBlockMetaInfo($document)
	{
		$lang = RequestContext::getInstance()->getLang();
		$content = $document->getContent();
		
		if (f_util_StringUtils::isEmpty($content))
		{
			return;
		}
		
		$contentDOM = new DOMDocument('1.0', 'UTF-8');
		if ($contentDOM->loadXML($content) === false)
		{
			throw new Exception("Unable to load page content");
		}
		
		$richtextCount = 0;
		$wordCount = 0;
		$blockCount = 0;
		// Process new page content
		$xpath = $this->getXPathInstance($contentDOM);
		foreach (theme_PagetemplateService::getInstance()->getChangeContentIds($document->getTemplate()) as $id)
		{
			$blockNodes = $xpath->query('//change:content[@id="' . $id . '"]//change:block');
			foreach ($blockNodes as $blockNode)
			{
				$type = $blockNode->getAttribute("type");
				
				if ($type == "richtext")
				{
					$richtextCount++;
					$wordCount += count(explode(' ', f_util_HtmlUtils::htmlToText($blockNode->textContent, false, true)));
				}
				else
				{
					$blockCount++;
				}
			}
		}
		
		if ($document->hasMeta('blockInfos'))
		{
			$blockInfosMeta = $document->getMetaMultiple('blockInfos');
		}
		else
		{
			$blockInfosMeta = array();
		}
		
		if (!isset($blockInfosMeta[$lang]))
		{
			$blockInfosMeta[$lang] = array();
		}
		$blockInfosMeta[$lang]['dynamicBlockCount'] = $blockCount;
		$blockInfosMeta[$lang]['richtextBlockCount'] = $richtextCount;
		$blockInfosMeta[$lang]['wordCount'] = $wordCount;
		
		$document->setMetaMultiple('blockInfos', $blockInfosMeta);
	}
	
	/**
	 * @param website_persistentdocument_page $document
	 */
	public function getBlockMetaInfos($document)
	{
		$metasAvailable = array("title" => array(), "description" => array(), "keywords" => array());
		$content = $document->getContent();
		
		if (f_util_StringUtils::isEmpty($content))
		{
			return $metasAvailable;
		}
		
		$contentDOM = new DOMDocument('1.0', 'UTF-8');
		if ($contentDOM->loadXML($content) === false)
		{
			throw new Exception("Unable to load page content");
		}
		$bs = block_BlockService::getInstance();
		
		// Process new page content
		$xpath = $this->getXPathInstance($contentDOM);
		try
		{
			$ids = theme_PagetemplateService::getInstance()->getChangeContentIds($document->getTemplate());
		}
		catch (TemplateNotFoundException $e)
		{
			Framework::exception($e);
			$ids = array();
		}
		foreach ($ids as $id)
		{
			$blockNodes = $xpath->query('//change:content[@id="' . $id . '"]//change:block');
			foreach ($blockNodes as $blockNode)
			{
				$type = $blockNode->getAttribute("type");
				
				if ($type != "richtext")
				{
					$blockInfoArray = $this->buildBlockInfo($type, $this->parseBlockParameters($blockNode), $blockNode->getAttribute('lang'), $blockNode->getAttribute('blockwidth'), $blockNode->getAttribute('editable') != 'false', $blockNode);
					$className = $bs->getBlockActionClassNameByType($type);
					if ($className === null)
					{
						continue;
					}
					
					$blockAction = new $className($type);
					if (isset($blockInfoArray['lang']))
					{
						$blockAction->setLang($blockInfoArray['lang']);
					}
					
					foreach ($blockInfoArray['parameters'] as $name => $value)
					{
						$blockAction->setConfigurationParameter($name, $value);
					}
					$blockConfig = $blockAction->getConfiguration();
					$blockInfo = block_BlockService::getInstance()->getBlockInfo($blockInfoArray["package"] . "_" . $blockInfoArray["name"]);
					if ($blockInfo === null)
					{
						Framework::warn(__METHOD__ . " This block has no block info. You should declare it in the blocks.xml config file and hide it if you need to.");
					}
					else if ($blockInfo->hasMeta() && $blockConfig->getEnablemetas())
					{
						list (, $moduleName) = explode('_', $blockInfoArray["package"]);
						$metaPrefix = $moduleName . "_" . f_util_StringUtils::lcfirst($blockInfoArray["name"]) . ".";
						
						$newMetas = array();
						foreach ($blockInfo->getTitleMetas() as $meta)
						{
							$newMetas[] = $metaPrefix . $meta;
						}
						$metasAvailable["title"] = array_merge($metasAvailable["title"], $newMetas);
						
						$newMetas = array();
						foreach ($blockInfo->getDescriptionMetas() as $meta)
						{
							$newMetas[] = $metaPrefix . $meta;
						}
						$metasAvailable["description"] = array_merge($metasAvailable["description"], $newMetas);
						
						$newMetas = array();
						foreach ($blockInfo->getKeywordsMetas() as $meta)
						{
							$newMetas[] = $metaPrefix . $meta;
						}
						$metasAvailable["keywords"] = array_merge($metasAvailable["keywords"], $newMetas);
					}
				}
			}
		}
		return $metasAvailable;
	}
	
	/**
	 * @param website_persistentdocument_page $document
	 * @param integer $parentNodeId
	 */
	protected function postSave($document, $parentNodeId)
	{
		$this->synchronizeReferences($document);
		if ($document->isPropertyModified('navigationtitle'))
		{
			website_MenuitemdocumentService::getInstance()->synchronizeLabelForRelatedMenuItems($document);
		}
	}
	
	/**
	 * @param website_persistentdocument_page $document
	 */
	protected function postDeleteLocalized($document)
	{
		website_MenuitemdocumentService::getInstance()->removeTranslationForRelatedMenuItems($document);
	}
	
	/**
	 * Returns TRUE if the given Page is publishable.
	 *
	 * @param website_persistentdocument_page $page
	 * @return boolean
	 */
	public function isPublishable($page)
	{
		return !f_util_StringUtils::isEmpty($page->getContent()) && parent::isPublishable($page);
	}
	
	/**
	 * Returns the full name of the page's template.
	 *
	 * @param website_persistentdocument_page $document
	 * @return string
	 */
	public function getTemplateName($document)
	{
		$template = theme_PagetemplateService::getInstance()->getByCodeName($document->getTemplate());
		if ($template)
		{
			return $template->getLabel();
		}
		return $document->getTemplate();
	}
	
	/**
	 * @see f_persistentdocument_DocumentService::getWebsiteId()
	 *
	 * @param website_persistentdocument_page $document
	 * @return integer
	 */
	public function getWebsiteId($document)
	{
		return intval($document->getMeta("websiteId"));
	}
	
	/**
	 * @param website_UrlRewritingService $urlRewritingService
	 * @param website_persistentdocument_page $document
	 * @param website_persistentdocument_website $website
	 * @param string $lang
	 * @param array $parameters
	 * @return f_web_Link | null
	 */
	public function getWebLink($urlRewritingService, $document, $website, $lang, $parameters)
	{
		if ($document->getIsHomePage())
		{
			$website = website_persistentdocument_website::getInstanceById($this->getWebsiteId($document));
			return $urlRewritingService->getRewriteLink($website, $lang, '/', $parameters);
		}
		return null;
	}
	
	/**
	 * @param website_persistentdocument_page $page
	 * @return website_persistentdocument_page
	 */
	public function getVersionOf($page)
	{
		if ($page instanceof website_persistentdocument_page)
		{
			return $page;
		}
		
		throw new Exception('Invalid ancestor Id for pageversion' . $page->getId());
	}
	
	/**
	 * @param website_persistentdocument_page $page
	 * @param boolean $isIndexPage
	 * @param boolean $userSetting
	 */
	public function setIsIndexPage($page, $isIndexPage, $userSetting = false)
	{
		try
		{
			$this->getTransactionManager()->beginTransaction();
			$page->setIsIndexPage($isIndexPage);
			if ($page->isModified())
			{
				$this->getPersistentProvider()->updateDocument($page);
				if ($page instanceof website_persistentdocument_pagegroup)
				{
					$versions = $page->getChildrenVersions();
					$pvs = website_PageversionService::getInstance();
					foreach ($versions as $version)
					{
						$pvs->setIsIndexPage($version, $isIndexPage, false);
					}
				}
			}
			$this->getTransactionManager()->commit();
		}
		catch (Exception $e)
		{
			$this->getTransactionManager()->rollBack($e);
		}
	}
	
	/**
	 * @param f_persistentdocument_PersistentDocument $parent
	 * @return website_persistentdocument_page || null
	 */
	public function getFirstPublished($parent)
	{
		return $this->createQuery()->add(Restrictions::published())->add(Restrictions::childOf($parent->getId()))->setMaxResults(1)->findUnique();
	}
	
	/**
	 * @param website_persistentdocument_page $page
	 * @param boolean $isHomePage
	 */
	public function setIsHomePage($page, $isHomePage)
	{
		try
		{
			$this->getTransactionManager()->beginTransaction();
			$page->setIsHomePage($isHomePage);
			if ($page->isModified())
			{
				$this->getPersistentProvider()->updateDocument($page);
				if ($page instanceof website_persistentdocument_pagegroup)
				{
					$versions = $page->getChildrenVersions();
					$pvs = website_PageversionService::getInstance();
					foreach ($versions as $version)
					{
						$pvs->setIsHomePage($version, $isHomePage);
					}
				}
			}
			$this->getTransactionManager()->commit();
		}
		catch (Exception $e)
		{
			$this->getTransactionManager()->rollBack($e);
		}
	}
	
	/**
	 * Sets the homepage for a website.
	 *
	 * @param website_persistentdocument_page $page
	 */
	public function makeHomePage($page)
	{
		$indexPage = DocumentHelper::getByCorrection($page);
		
		$website = website_WebsiteService::getInstance()->getByDocument($indexPage);
		if ($website)
		{
			$website->getDocumentService()->setHomePage($website, $indexPage);
		}
	}
	
	/**
	 * Sets the index page for a topic.
	 *
	 * @param website_persistentdocument_page $page
	 * @param boolean $userSetting
	 */
	public function makeIndexPage($page, $userSetting = false)
	{
		$indexPage = DocumentHelper::getByCorrection($page);
		$topic = $indexPage->getTopic();
		if ($topic)
		{
			website_TopicService::getInstance()->setIndexPage($topic, $indexPage, $userSetting);
		}
		else
		{
			$parent = $this->getParentOf($page);
			if ($parent instanceof website_persistentdocument_website)
			{
				$parent->getDocumentService()->setHomePage($parent, $indexPage);
			}
		}
	}
	
	/**
	 * @param website_persistentdocument_page $page
	 * @param boolean $userSetting
	 */
	public function removeIndexPage($page, $userSetting = false)
	{
		$topic = website_TopicService::getInstance()->getParentByPage($page);
		if ($topic)
		{
			website_TopicService::getInstance()->setIndexPage($topic, null, $userSetting);
		}
	}
	
	/**
	 * @param website_persistentdocument_page $document The document to move.
	 * @param integer $destId ID of the destination node.
	 */
	public function onMoveToStart($document, $destId)
	{
		$status = $document->getPublicationstatus();
		if ($status == 'CORRECTION' || $status == 'WORKFLOW')
		{
			throw new BaseException('Unable to move this document in this state', 'modules.website.errors.unable-to-move-document');
		}
		
		$currentParent = $this->getParentOf($document);
		if ($currentParent !== null && $currentParent->getId() === $destId)
		{
			// If the parent doesn't change there's nothing to do...
			return;
		}
		
		// Remove index page
		if ($document instanceof website_persistentdocument_page && $document->getIsIndexPage())
		{
			// TODO: document the precise use of the second argument of removeIndexPage ???
			$document->getDocumentService()->removeIndexPage($document, true);
		}
		
		$ts = TagService::getInstance();
		foreach ($ts->getTags($document) as $tag)
		{
			if ($ts->isFunctionalTag($tag))
			{
				$ts->removeTag($document, $tag);
			}
		}
	}
	
	/**
	 * @param website_persistentdocument_page $document
	 * @param integer $destId
	 */
	protected function onDocumentMoved($document, $destId)
	{
		// update websiteId meta if needed
		$destination = DocumentHelper::getDocumentInstance($destId);
		if ($destination instanceof website_persistentdocument_website)
		{
			$newWebsiteId = $destination->getId();
		}
		else
		{
			$newWebsiteId = $destination->getMeta("websiteId");
		}
		if ($document->getMeta("websiteId") != $newWebsiteId)
		{
			$document->setMeta("websiteId", $newWebsiteId);
			$document->saveMeta();
		}
		
		// When a page is moved from a topic to another, reindex it.
		$is = indexer_IndexService::getInstance();
		if (!is_null($is))
		{
			$is->update($document);
		}
		// Regenerate the page cache
		$this->synchronizeReferences($document);
	}
	
	/**
	 * @param website_persistentdocument_page $page
	 */
	private function createFunctionalPage($page)
	{
		if (Framework::isDebugEnabled())
		{
			Framework::debug(__METHOD__ . '(' . $page->__toString() . ')');
		}
		
		if ($page instanceof website_persistentdocument_pagereference)
		{
			$basePage = DocumentHelper::getDocumentInstance($page->getReferenceofid());
		}
		else
		{
			$basePage = $page;
		}
		
		if (Framework::isDebugEnabled())
		{
			Framework::debug(__METHOD__ . '(' . $page->__toString() . ') -> original page :' . $basePage->__toString());
		}
		
		$pageTreeNode = TreeService::getInstance()->getInstanceByDocument($page);
		$parentTreeNode = $pageTreeNode->getParent();
		
		$query = $this->getPersistentProvider()->createQuery('modules_website/topic')->add(Restrictions::descendentOf($parentTreeNode->getId()));
		$topics = $query->find();
		foreach ($topics as $topic)
		{
			$this->setPageReferenceInTopics($topic, $basePage);
		}
	}
	
	/**
	 * @param website_persistentdocument_topic $topic
	 * @param website_persistentdocument_page $page
	 * @return website_persistentdocument_page
	 */
	public function createPageReference($topic, $page)
	{
		if ($page instanceof website_persistentdocument_pagereference)
		{
			$basePage = $this->getDocumentInstance($page->getReferenceofid());
		}
		else
		{
			$basePage = $page;
		}
		return $this->setPageReferenceInTopics($topic, $basePage);
	}
	
	/**
	 * @param website_persistentdocument_topic $topic
	 * @param website_persistentdocument_page $page
	 * @return website_persistentdocument_page
	 */
	private function setPageReferenceInTopics($topic, $page)
	{
		$query = $this->getPersistentProvider()->createQuery('modules_website/pagereference')->add(Restrictions::childOf($topic->getId()))->add(Restrictions::eq('referenceofid', $page->getId()));
		
		$pageReference = $query->findUnique();
		$setAsIndex = false;
		
		if (is_null($pageReference))
		{
			if (Framework::isInfoEnabled())
			{
				Framework::info(__METHOD__ . ' Add new page reference');
			}
			
			$pageReference = website_PagereferenceService::getInstance()->getNewDocumentInstance();
			$parentContainer = $topic->getDocumentService()->getParentOf($topic);
			$index = website_PagereferenceService::getInstance()->createQuery()->setProjection(Projections::property('isIndexPage', 'isIndexPage'))->add(Restrictions::childOf($parentContainer->getId()))->add(Restrictions::eq('referenceofid', $page->getId()))->findUnique();
			
			if ($index)
			{
				$setAsIndex = ($index['isIndexPage'] == true);
			}
			else
			{
				$setAsIndex = $page->getIsIndexPage();
			}
		}
		
		website_PagereferenceService::getInstance()->updatePageReference($pageReference, $page, $topic->getId());
		$pageReference->save($topic->getId());
		if ($setAsIndex)
		{
			$pageReference->getDocumentService()->makeIndexPage($pageReference, false);
		}
		return $pageReference;
	}
	
	/**
	 * @param website_persistentdocument_page $page
	 */
	private function removeFunctionalPage($page)
	{
		$tags = TagService::getInstance()->getTags($page);
		if (count($tags) == 0)
		{
			$pageTreeNode = TreeService::getInstance()->getInstanceByDocument($page);
			if (is_null($pageTreeNode))
			{
				Framework::debug(__METHOD__ . '(' . $page->__toString() . ') -> Canceled not in tree');
				return;
			}
			
			if ($page instanceof website_persistentdocument_pagereference)
			{
				$pageId = $page->getReferenceofid();
				$deletePage = true;
			}
			else
			{
				$pageId = $page->getId();
				$deletePage = false;
			}
			$parentTreeNode = $pageTreeNode->getParent();
			$query = $this->getPersistentProvider()->createQuery('modules_website/pagereference')->add(Restrictions::eq('referenceofid', $pageId));
			
			//Tag deplacer d'une page reference on ne prend que les descendants de rubrique
			if ($deletePage)
			{
				$query->add(Restrictions::descendentOf($parentTreeNode->getId()));
			}
			
			$pagesReference = $query->find();
			$pgrefService = website_PagereferenceService::getInstance();
			
			foreach ($pagesReference as $pageReference)
			{
				$pgrefService->purgeDocument($pageReference);
			}
			
			if ($deletePage)
			{
				$pgrefService->purgeDocument($page);
			}
		}
	}
	
	/**
	 * @param website_persistentdocument_page $document
	 * @param string $tag
	 * @return void
	 */
	public function tagAdded($document, $tag)
	{
		if (TagService::getInstance()->isFunctionalTag($tag))
		{
			$this->createFunctionalPage($document);
		}
	}
	
	/**
	 * @param website_persistentdocument_page $document
	 * @param string $tag
	 * @return void
	 */
	public function tagRemoved($document, $tag)
	{
		$tagService = TagService::getInstance();
		
		if ($tagService->isFunctionalTag($tag))
		{
			$this->removeFunctionalPage($document);
			if (Framework::isDebugEnabled())
			{
				Framework::debug('FUNCTIONAL TAG');
			}
			
			$pageNode = TreeService::getInstance()->getInstanceByDocument($document);
			if (is_null($pageNode))
			{
				return;
			}
			
			$ancestors = $pageNode->getAncestors();
			if (Framework::isDebugEnabled())
			{
				Framework::debug('COUNT ANCESTORS : ' . count($ancestors));
			}
			
			$topic = array_pop($ancestors)->getPersistentDocument();
			if (Framework::isDebugEnabled())
			{
				Framework::debug('TOPIC ' . $topic->__toString());
			}
			
			if (!$topic instanceof website_persistentdocument_topic)
			{
				return;
			}
			
			$pageRefs = $this->getPersistentProvider()->createQuery('modules_website/pagereference')->add(Restrictions::eq('referenceofid', $document->getId()))->find();
			
			foreach ($pageRefs as $pageRef)
			{
				if (Framework::isDebugEnabled())
				{
					Framework::debug('REMOVE TAG ON ' . $pageRef->__toString());
				}
				$tagService->removeTag($pageRef, $tag);
			}
			
			$parentTopic = array_pop($ancestors)->getPersistentDocument();
			if (Framework::isDebugEnabled())
			{
				Framework::debug('PARENTTOPIC ' . $parentTopic->__toString());
			}
			
			if (!$parentTopic instanceof website_persistentdocument_topic)
			{
				return;
			}
			
			$query = $this->getPersistentProvider()->createQuery('modules_website/page')->add(Restrictions::descendentOf($parentTopic->getId(), 1))->add(Restrictions::hasTag($tag));
			$page = $query->findUnique();
			
			if (Framework::isDebugEnabled())
			{
				Framework::debug('PAGE ' . $page);
			}
			
			if (is_null($page))
			{
				return;
			}
			
			if ($page instanceof website_persistentdocument_pagereference)
			{
				$page = DocumentHelper::getDocumentInstance($page->getReferenceofid());
			}
			
			$this->setPageReferenceInTopics($topic, $page);
			
			$query = $this->getPersistentProvider()->createQuery('modules_website/topic')->add(Restrictions::descendentOf($topic->getId()));
			
			$topics = $query->find();
			foreach ($topics as $topic)
			{
				$this->setPageReferenceInTopics($topic, $page);
			}
		}
	}
	
	/**
	 * @param website_persistentdocument_page $fromDocument
	 * @param website_persistentdocument_page $toDocument
	 * @param string $tag
	 * @return void
	 */
	public function tagMovedFrom($fromDocument, $toDocument, $tag)
	{
		if (Framework::isDebugEnabled())
		{
			Framework::debug(__METHOD__ . '(' . $fromDocument->__toString() . ',' . $tag . ')');
		}
		
		if (TagService::getInstance()->isFunctionalTag($tag))
		{
			$this->removeFunctionalPage($fromDocument);
		}
	}
	
	/**
	 * @param website_persistentdocument_page $fromDocument
	 * @param website_persistentdocument_page $toDocument
	 * @param string $tag
	 * @return void
	 */
	public function tagMovedTo($fromDocument, $toDocument, $tag)
	{
		if (Framework::isDebugEnabled())
		{
			Framework::debug(__METHOD__ . '(' . $toDocument->__toString() . ',' . $tag . ')');
		}
		
		if (TagService::getInstance()->isFunctionalTag($tag))
		{
			$this->createFunctionalPage($toDocument);
		}
	}
	
	/**
	 * @param integer $pageCount
	 * @return array<website_persistentdocument_page>
	 */
	public function getLastModified($pageCount = 5)
	{
		$query = $this->createQuery()->add(Restrictions::ne('model', 'modules_website/pagereference'))->add(Restrictions::ne('model', 'modules_website/pagegroup'))->add(Restrictions::ne('publicationstatus', 'DEPRECATED'))->addOrder(Order::desc('document_modificationdate'))->setMaxResults($pageCount);
		$pageModel = f_persistentdocument_PersistentDocumentModel::getInstanceFromDocumentModelName('modules_website/page');
		if ($pageModel->useCorrection())
		{
			$query->add(Restrictions::isNull('correctionofid'));
		}
		return $query->find();
	}
	
	/**
	 * @param website_persistentdocument_page $newDocument
	 * @param website_persistentdocument_page $originalDocument
	 * @param integer $parentNodeId
	 */
	protected function preDuplicate($newDocument, $originalDocument, $parentNodeId)
	{
		$newDocument->setIsIndexPage(false);
		$newDocument->setIsHomePage(false);
	}
	
	/**
	 * @param website_persistentdocument_page $page
	 */
	private function initContent($page)
	{
		$page->setContent('<change:contents xmlns:change="' . self::CHANGE_PAGE_EDITOR_NS . '" />');
	}
	
	/**
	 * @param website_persistentdocument_page $page
	 */
	public function setDefaultContent($page)
	{
		$this->initContent($page);
		$this->save($page);
	}
	
	/**
	 * @var integer
	 * Set by website/DisplayAction.
	 */
	private $currentPageId = null;
	
	/**
	 * @var integer[]
	 */
	private $currentPageAncestorsIds = array();
	
	private $currentPageAncestors = array();
	
	/**
	 * This function set the currentPageId and calculate :
	 * 	- currentPageAncestors[Ids]
	 *  - currentWebsite
	 * @param integer $currentPageId
	 */
	public function setCurrentPageId($currentPageId)
	{
		if (Framework::isDebugEnabled())
		{
			Framework::debug(__METHOD__ . ' ' . $currentPageId);
		}
		$this->currentPageId = $currentPageId;
		$this->currentPageAncestorsIds = array();
		$this->currentPageAncestors = array();
		$page = DocumentHelper::getDocumentInstance($this->currentPageId);
		$ancestors = $page->getDocumentService()->getAncestorsOf($page);
		foreach ($ancestors as $document)
		{
			if ($document instanceof website_persistentdocument_website)
			{
				$this->currentPageAncestors[] = $document;
				$this->currentPageAncestorsIds[] = $document->getId();
				website_WebsiteService::getInstance()->setCurrentWebsite($document);
			}
			elseif ($document instanceof website_persistentdocument_topic)
			{
				$this->currentPageAncestors[] = $document;
				$this->currentPageAncestorsIds[] = $document->getId();
			}
		}
	}
	
	/**
	 * @return integer
	 */
	public function getCurrentPageId()
	{
		return $this->currentPageId;
	}
	
	/**
	 * @return website_persistentdocument_page
	 */
	function getCurrentPage()
	{
		return $this->getDocumentInstance($this->currentPageId, "modules_website/page");
	}
	
	/**
	 * @return array the current page ancestors ids
	 */
	public function getCurrentPageAncestorsIds()
	{
		return $this->currentPageAncestorsIds;
	}
	
	/**
	 * @return array the current page ancestors
	 */
	public function getCurrentPageAncestors()
	{
		return $this->currentPageAncestors;
	}
	
	const CHANGE_PAGE_EDITOR_NS = "http://www.rbs.fr/change/1.0/schema";
	const CHANGE_TEMPLATE_TYPE_HTML = "html";
	const CHANGE_TEMPLATE_TYPE_XUL = "xul";
	
	/**
	 * Extract the page's "full text" , that is the static richtexts
	 * inside the XML's page content.
	 *
	 * @param website_persistentdocument_page $page
	 * @return string
	 */
	public function getFullTextContent($page)
	{
		$result = "";
		$pageContent = $page->getContent();
		if ($pageContent === null)
		{
			return $result;
		}
		
		$contentDOM = new DOMDocument('1.0', 'UTF-8');
		if ($contentDOM->loadXML($pageContent) == false)
		{
			Framework::warn(__METHOD__ . ': page content is not a valid XML. Full text content can not be extracted');
			return $result;
		}
		// Process new page content
		$xpath = $this->getXPathInstance($contentDOM);
		foreach (theme_PagetemplateService::getInstance()->getChangeContentIds($page->getTemplate()) as $id)
		{
			$newRichtextNodes = $xpath->query('//change:content[@id="' . $id . '"]//change:richtextcontent');
			foreach ($newRichtextNodes as $richtTextNode)
			{
				$result .= ' ' . $richtTextNode->nodeValue;
			}
		}
		return f_util_HtmlUtils::htmlToText($result, false);
	}
	
	/**
	 * Update the content of the page
	 *
	 * @param website_persistentdocument_page $page
	 * @param string $content
	 */
	public function updatePageContent($page, $content)
	{
		$newContentDOM = new DOMDocument('1.0', 'UTF-8');
		$newContentDOM->loadXML($content);
		$this->cleanRichTextContent($newContentDOM);
		
		//change:richtextcontent
		$existingContent = $page->getContent();
		if (f_util_StringUtils::isEmpty($existingContent))
		{
			$page->setContent($content);
			return;
		}
		// Load the existing content
		$existingContentDOM = $this->getDomFromPageContent($page);
		$existingContentXPath = $this->getXPathInstance($existingContentDOM);
		
		$this->doBlockCallbacks($page, $existingContentDOM, $newContentDOM);
		
		$contentNodes = $newContentDOM->getElementsByTagNameNS(self::CHANGE_PAGE_EDITOR_NS, 'content');
		foreach ($contentNodes as $contentNode)
		{
			$contentId = $contentNode->getAttribute("id");
			$matchingPlaceHolders = $existingContentXPath->query(".//change:content[@id=\"$contentId\"]");
			if ($matchingPlaceHolders->length == 1)
			{
				$placeHolder = $matchingPlaceHolders->item(0);
				$importedNode = $existingContentDOM->importNode($contentNode, true);
				$placeHolder->parentNode->insertBefore($importedNode, $placeHolder);
				$placeHolder->parentNode->removeChild($placeHolder);
			}
			else
			{
				$importedNode = $existingContentDOM->importNode($contentNode, true);
				$existingContentDOM->documentElement->appendChild($importedNode);
			}
		}
		
		$page->setContent($existingContentDOM->saveXML());
	}
	
	/**
	 * @param website_persistentdocument_page $page
	 * @return DOMDocument | null
	 */
	private function getDomFromPageContent($page)
	{
		$doc = new DOMDocument('1.0', 'UTF-8');
		$content = $page->getContent();
		if ($content !== null)
		{
			$doc->loadXML($content);
		}
		return $doc;
	}
	
	private function hasBlockInOtherLangs($page, $type, &$otherLangsBlocks)
	{
		$rq = RequestContext::getInstance();
		$contextLang = $rq->getLang();
		foreach ($page->getI18nInfo()->getLangs() as $lang)
		{
			if ($lang === $contextLang)
			{
				continue;
			}
			if (isset($otherLangsBlocks[$lang]))
			{
				$otherLangBlocks = $otherLangsBlocks[$lang];
			}
			else
			{
				try
				{
					$rq->beginI18nWork($lang);
					if (f_util_StringUtils::isNotEmpty($page->getContent()))
					{
						$otherLangDom = f_util_DOMUtils::fromString($page->getContent());
						$otherLangBlocks = $this->getBlocksFromDom($otherLangDom);
						$otherLangsBlocks[$lang] = $otherLangBlocks;
					}
					else
					{
						$otherLangBlocks = array();
						$otherLangsBlocks[$lang] = $otherLangBlocks;
					}
					$rq->endI18nWork();
				}
				catch (Exception $e)
				{
					$rq->endI18nWork($e);
				}
			}
			
			if (isset($otherLangBlocks[$type]))
			{
				return true;
			}
		}
		return false;
	}
	
	/**
	 * @param website_persistentdocument_page $page
	 * @param DOMDocument $oldPageContentDom
	 * @param DOMDocument $newPageContentDom
	 */
	private function doBlockCallbacks($page, $oldPageContentDom, $newPageContentDom)
	{
		$oldBlocks = $this->getBlocksFromDom($oldPageContentDom);
		$newBlocks = $this->getBlocksFromDom($newPageContentDom);
		
		$otherLangsBlocks = array();
		
		foreach ($newBlocks as $type => $newBlock)
		{
			if (!isset($oldBlocks[$type]))
			{
				$hasBlock = $this->hasBlockInOtherLangs($page, $type, $otherLangsBlocks);
				$newBlock->onPageInsertion($page, !$hasBlock);
			}
		}
		foreach ($oldBlocks as $type => $oldBlock)
		{
			if (!isset($newBlocks[$type]))
			{
				$hasBlock = $this->hasBlockInOtherLangs($page, $type, $otherLangsBlocks);
				$oldBlock->onPageRemoval($page, !$hasBlock);
			}
		}
	}
	
	/**
	 * @param DOMDocument $dom
	 * @return array<String, website_BlockAction>
	 */
	private function getBlocksFromDom($dom)
	{
		$blocks = array();
		if ($dom->documentElement)
		{
			$bs = block_BlockService::getInstance();
			$blockElems = $dom->getElementsByTagNameNS(self::CHANGE_PAGE_EDITOR_NS, 'block');
			foreach ($blockElems as $blockElem)
			{
				$type = $blockElem->getAttribute("type");
				if ($bs->isSpecialBlock($type))
				{
					continue;
				}
				if (!isset($blocks[$type]))
				{
					$blockClassName = $bs->getBlockActionClassNameByType($type);
					if ($blockClassName !== null)
					{
						$class = new ReflectionClass($blockClassName);
						if ($class->implementsInterface("website_PageBlock"))
						{
							$block = $class->newInstance($type);
							$blockInfo = $this->buildBlockInfo($type, $this->parseBlockParameters($blockElem), $blockElem->getAttribute('lang'), $blockElem->getAttribute('blockwidth'), $blockElem->getAttribute('editable') != 'false', $blockElem);
							
							if (isset($blockInfo['lang']))
							{
								$block->setLang($blockInfo['lang']);
							}
							
							foreach ($blockInfo['parameters'] as $name => $value)
							{
								$block->setConfigurationParameter($name, $value);
							}
							
							$blocks[$type] = $block;
						}
					}
				}
			}
		}
		return $blocks;
	}
	
	private function getBlockClassNameFromType($type)
	{
		$typeInfo = explode("_", $type);
		if (count($typeInfo) == 3)
		{
			$className = $typeInfo[1] . '_Block' . ucfirst($typeInfo[2]) . 'Action';
			if (class_exists($className))
			{
				return $className;
			}
			else
			{
				Framework::warn(__METHOD__ . " : class [$className] not found");
			}
		}
		return null;
	}
	
	/**
	 * @param string $textContent
	 * @return string
	 */
	public function getCleanContent($textContent)
	{
		$contentDOM = new DOMDocument('1.0', 'UTF-8');
		$contentDOM->loadXML($textContent);
		$this->cleanRichTextContent($contentDOM);
		return $contentDOM->saveXML();
	}
	
	/**
	 * @param DOMDocument $domContent
	 */
	private function cleanRichTextContent($domContent)
	{
		$richtextContentNodes = $domContent->getElementsByTagNameNS(self::CHANGE_PAGE_EDITOR_NS, 'richtextcontent');
		foreach ($richtextContentNodes as $contentNode)
		{
			if ($contentNode->childNodes->length == 1)
			{
				$cdata = $contentNode->firstChild;
				$content = $cdata->data;
				$cdata->data = website_XHTMLCleanerHelper::clean($content);
			}
		}
	}
	
	/**
	 * @param DOMDocument $DOMDocument
	 * @return DOMXPath
	 */
	private function getXPathInstance($DOMDocument)
	{
		$resultXPath = new DOMXPath($DOMDocument);
		$resultXPath->registerNameSpace('change', self::CHANGE_PAGE_EDITOR_NS);
		return $resultXPath;
	}
	
	/**
	 * @return task_persistentdocument_usertask[]
	 */
	public final function getPendingTasksForCurrentUser()
	{
		$pageModel = f_persistentdocument_PersistentDocumentModel::getInstance('website', 'page');
		if (!$pageModel->hasWorkflow())
		{
			return array();
		}
		$query = task_UsertaskService::getInstance()->createQuery();
		$query->add(Restrictions::eq('user', users_UserService::getInstance()->getCurrentUser()->getId()));
		$query->add(Restrictions::published());
		$query->add(Restrictions::eq('workitem.transition.taskid', $pageModel->getWorkflowStartTask()));
		$query->addOrder(Order::desc('document_creationdate'));
		$query->setMaxResults(50);
		return $query->find();
	}
	
	// Orphan pages related methods.
	

	/**
	 * @return website_persistentdocument_page[]
	 */
	public final function getOrphanPages()
	{
		$query = $this->createQuery()->add(Restrictions::published())->add(Restrictions::eq('isorphan', true))->addOrder(Order::desc('document_modificationdate'))->setMaxResults(50);
		return $query->find();
	
	}
	
	/**
	 * @return website_persistentdocument_page[]
	 */
	public final function getOrphanPagesForWebsiteId($websiteId)
	{
		$query = $this->createQuery()->add(Restrictions::published())->add(Restrictions::eq('isorphan', true))->addOrder(Order::desc('document_modificationdate'))->add(Restrictions::descendentOf($websiteId))->setMaxResults(50);
		return $query->find();
	
	}
	
	/**
	 * @return integer
	 */
	public final function getOrphanPagesCount()
	{
		$query = $this->createQuery()->add(Restrictions::published())->add(Restrictions::eq('isorphan', true))->addOrder(Order::desc('document_creationdate'))->setProjection(Projections::rowCount('count'));
		$result = $query->find();
		return $result[0]['count'];
	
	}
	
	/**
	 * @return integer
	 */
	public final function getOrphanPagesCountForWebsiteId($websiteId)
	{
		$query = $this->createQuery()->add(Restrictions::published())->add(Restrictions::eq('isorphan', true))->addOrder(Order::desc('document_creationdate'))->add(Restrictions::descendentOf($websiteId))->setProjection(Projections::rowCount('count'));
		$result = $query->find();
		return $result[0]['count'];
	
	}
	
	/**
	 * @return integer[]
	 */
	public function getTaggedPageIds()
	{
		$query = $this->createQuery()->add(Restrictions::published())->add(Restrictions::isTagged())->add(Restrictions::eq('model', 'modules_website/page'))->setProjection(Projections::property('id', 'id'));
		$result = array();
		foreach ($query->find() as $row)
		{
			$result[] = intval($row['id']);
		}
		return $result;
	}
	
	/**
	 * Add custom log informations
	 * @param f_persistentdocument_PersistentDocument $document
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
	 * @param website_persistentdocument_page $page
	 * @return integer
	 */
	public function getSkinId($page)
	{
		$skin = $this->getSkin($page);
		return ($skin !== null) ? $skin->getId() : null;
	}
	
	/**
	 * @param website_persistentdocument_page $page
	 * @return skin_persistentdocument_skin
	 */
	public function getSkin($page)
	{
		$skin = $page->getSkin();
		if ($skin !== null && $skin->isPublished())
		{
			return $skin;
		}
		$ancestors = array_reverse($this->getAncestorsOf($page));
		foreach ($ancestors as $ancestor)
		{
			if (($ancestor instanceof website_persistentdocument_website) || ($ancestor instanceof website_persistentdocument_topic))
			{
				$skin = $ancestor->getSkin();
				if ($skin !== null && $skin->isPublished())
				{
					return $skin;
				}
			}
		}
		return null;
	}
	
	/**
	 * @param website_persistentdocument_page $document
	 * @param string $forModuleName
	 * @param array $allowedSections
	 * @return array
	 */
	public function getResume($document, $forModuleName, $allowedSections = null)
	{
		$data = parent::getResume($document, $forModuleName, $allowedSections);
		
		if ($document->isContextLangAvailable())
		{
			$lang = RequestContext::getInstance()->getLang();
		}
		else
		{
			$lang = $document->getLang();
		}
		$blockCount = $richtextCount = $wordCount = 0;
		
		if ($document->hasMeta('blockInfos'))
		{
			$blockInfos = $document->getMetaMultiple('blockInfos');
			if (isset($blockInfos[$lang]))
			{
				$blockCount = $blockInfos[$lang]['dynamicBlockCount'];
				$richtextCount = $blockInfos[$lang]['richtextBlockCount'];
				$wordCount = $blockInfos[$lang]['wordCount'];
			}
		}
		
		$contentData = array(
			'pagecomposition' => LocaleService::getInstance()->trans('m.website.bo.doceditor.current-page-composition', array('ucf'), array(
				"blockCount" => $blockCount, "richtextCount" => $richtextCount)));
		
		if ($wordCount == 0)
		{
			$contentData['freecontent'] = LocaleService::getInstance()->trans('m.website.bo.doceditor.current-word-count-empty', array('ucf'));
		}
		else if ($wordCount == 1)
		{
			$contentData['freecontent'] = LocaleService::getInstance()->trans('m.website.bo.doceditor.current-word-count-singular', array('ucf'));
		}
		else
		{
			$contentData['freecontent'] = LocaleService::getInstance()->trans('m.website.bo.doceditor.current-word-count', array('ucf'), array(
				'wordCount' => $wordCount));
		}
		$data['content'] = $contentData;
		return $data;
	}
	
	/**
	 * Remove spacer
	 * @param DOMDocument $pageContent
	 */
	private function patchOldPageContent($pageContent)
	{
		$spacers = array();
		foreach ($pageContent->getElementsByTagName('cblock') as $spacer)
		{
			$spacers[] = $spacer;
		}
		
		foreach ($spacers as $spacer)
		{
			$rowNode = $spacer->parentNode;
			
			$width = intval($spacer->getAttribute('width'));
			$width = $width < 12 ? 12 : $width;
			
			$height = intval($spacer->getAttribute('height'));
			$height = $height < 12 ? 12 : $height;
			
			$removeDrop = $spacer->previousSibling;
			$previousBlock = $removeDrop->previousSibling;
			if ($previousBlock)
			{
				$previousBlock->setAttribute('marginRight', $width);
			}
			$rowNode->removeChild($spacer);
			$rowNode->removeChild($removeDrop);
			
			if ($rowNode->childNodes->length < 3)
			{
				
				$column = $rowNode->parentNode;
				$removeDrop = $rowNode->previousSibling;
				$previousRow = $removeDrop->previousSibling;
				if ($previousRow)
				{
					$previousRow->setAttribute('marginBottom', $height);
				}
				$column->removeChild($rowNode);
				$column->removeChild($removeDrop);
			}
		}
	}
	
	/**
	 * Returns the content of the page ready to be use by the backoffice editor.
	 *
	 * @param website_persistentdocument_page $page
	 * @return string
	 */
	public function getContentForEdition($page, &$editorType = null)
	{
		$wsprs = website_PageRessourceService::getInstance();
		$pageContent = $wsprs->getBackpagetemplateAsDOMDocument($page);
		$this->patchOldPageContent($pageContent);
		
		$blocks = $this->generateBlocks($pageContent);
		$wsprs->buildBlockContainerForBackOffice($pageContent, $blocks);
		
		$pageContent->preserveWhiteSpace = false;
		$xulContent = $pageContent->saveXML($pageContent->documentElement);
		
		$controller = website_BlockController::getInstance();
		$controller->setPage($page);
		$controller->getContext()->setAttribute(website_BlockAction::BLOCK_BO_MODE_ATTRIBUTE, true);
		$this->populateHTMLBlocks($controller, $blocks);
		
		foreach ($blocks as $blockId => $block)
		{
			$html = $block['html'];
			$tmpDoc = new DOMDocument('1.0', 'UTF-8');
			
			$baseStyle = $block['editable'] ? 'display:none;' : '';
			if ($block['name'] === 'staticrichtext')
			{
				$tmpDoc->loadXML($html);
			}
			else
			{
				// If the block has no text content and contains no image, show the block's name.
				if (f_util_StringUtils::isEmpty(f_util_HtmlUtils::htmlToText($html)) && strpos($html, '<img') === false)
				{
					$html = "<strong>" . $this->getBlockLabelFromBlockType($block['type']) . "</strong>";
				}
				else
				{
					$html = f_util_HtmlUtils::cleanHtmlForBackofficeEdition($html);
				}
				$tmpDoc->loadXML('<div xmlns="http://www.w3.org/1999/xhtml" style="' . $baseStyle . '" class="' . $block['class'] . '">' . $html . '</div>');
			}
			if ($tmpDoc->documentElement)
			{
				$xmlContent = $tmpDoc->saveXML($tmpDoc->documentElement);
			}
			else
			{
				Framework::warn(__METHOD__ . ' ' . $block['type'] . ' html: ' . $html);
				$class = str_replace('_', '-', $block['type'] . ' ' . $block['package']);
				$xmlContent = '<div xmlns="http://www.w3.org/1999/xhtml" style="' . $baseStyle . '" class="' . $class . '"><strong style="color:red;">' . $this->getBlockLabelFromBlockType($block['type']) . ' : Invalid XML</strong></div>';
			}
			$xulContent = str_replace('<htmlblock_' . $blockId . '/>', $xmlContent, $xulContent);
		}
		return $xulContent;
	}
	
	private function getBlockLabelFromBlockType($blockType)
	{
		try
		{
			return LocaleService::getInstance()->trans(block_BlockService::getInstance()->getBlockLabelFromBlockName($blockType));
		}
		catch (Exception $e)
		{
			Framework::exception($e);
		}
		return $blockType;
	}
	
	/**
	 * @param websitePage $pageContext
	 * @return website_Breadcrumb
	 */
	public function getDefaultBreadcrumb($pageContext)
	{
		$pageDocument = $pageContext->getPersistentPage();
		
		$breadcrumb = new website_Breadcrumb();
		$lastAncestorPage = null;
		
		foreach ($pageContext->getAncestorIds() as $ancestorId)
		{
			$ancestor = DocumentHelper::getDocumentInstance($ancestorId);
			if (!$ancestor->isPublished())
			{
				continue;
			}
			
			if ($ancestor instanceof website_persistentdocument_website)
			{
				$lastAncestorPage = $ancestor->getIndexPage();
				if ($lastAncestorPage)
				{
					$navigationtitle = $lastAncestorPage->getNavigationLabel();
					$href = $lastAncestorPage !== $pageDocument ? LinkHelper::getDocumentUrl($ancestor) : null;
					if ($navigationtitle)
					{
						$breadcrumb->addElement($navigationtitle, $href);
						if ($href)
						{
							$pageContext->addLink("home", "text/html", $href, $navigationtitle);
						}
					}
				}
			}
			else if ($ancestor instanceof website_persistentdocument_topic)
			{
				if ($ancestor->getNavigationVisibility() == website_ModuleService::VISIBLE)
				{
					$navigationtitle = $ancestor->getNavigationLabel();
					if ($navigationtitle)
					{
						$lastAncestorPage = $ancestor->getIndexPage();
						$href = ($lastAncestorPage && $lastAncestorPage !== $pageDocument) ? LinkHelper::getDocumentUrl($ancestor) : null;
						$breadcrumb->addElement($navigationtitle, $href);
					}
				}
			}
		}
		
		if ($lastAncestorPage !== $pageDocument || $pageDocument->getNavigationVisibility() == website_ModuleService::VISIBLE)
		{
			if ($pageDocument->getNavigationVisibility() != website_ModuleService::VISIBLE)
			{
				$globalRequest = change_Controller::getInstance()->getRequest();
				if ($globalRequest->hasParameter('detail_cmpref'))
				{
					$detail = DocumentHelper::getDocumentInstanceIfExists(intval($globalRequest->getParameter('detail_cmpref')));
					if ($detail)
					{
						$navigationtitle = $detail->getDocumentService()->getNavigationLabel($detail);
						if ($navigationtitle)
						{
							$breadcrumb->addElement($navigationtitle);
						}
					}
				}
			}
			else
			{
				$breadcrumb->addElement($pageContext->getNavigationtitle());
			}
		}
		
		return $breadcrumb;
	}
	
	/**
	 * @param website_persistentdocument_page $page
	 * @param array $blockInfo
	 * @return string
	 */
	public function getBlockContentForEdition($page, $blockInfo)
	{
		$blocks = array(1 => $blockInfo);
		$controller = website_BlockController::getInstance();
		$controller->setPage($page);
		$controller->getContext()->setAttribute(website_BlockAction::BLOCK_BO_MODE_ATTRIBUTE, true);
		$this->populateHTMLBlocks($controller, $blocks);
		
		$block = $blocks[1];
		$html = $block['html'];
		$tmpDoc = new DOMDocument('1.0', 'UTF-8');
		if ($block['name'] === 'staticrichtext')
		{
			$tmpDoc->loadXML($html);
		}
		else
		{
			// If the block has no text content and contains no image, show the block's name.
			if (f_util_StringUtils::isEmpty(f_util_HtmlUtils::htmlToText($html)) && strpos($html, '<img') === false)
			{
				$html = "<strong>" . $this->getBlockLabelFromBlockType($block['type']) . "</strong>";
			}
			else
			{
				$html = f_util_HtmlUtils::cleanHtmlForBackofficeEdition($html);
			}
			$class = str_replace('_', '-', $block['type'] . ' ' . $block['package']);
			$tmpDoc->loadXML('<div xmlns="http://www.w3.org/1999/xhtml" class="' . $class . '">' . $html . '</div>');
		}
		if ($tmpDoc->documentElement)
		{
			return $tmpDoc->saveXML($tmpDoc->documentElement);
		}
		else
		{
			return '<div xmlns="http://www.w3.org/1999/xhtml" class="' . $class . '"><strong style="color:red;">' . $this->getBlockLabelFromBlockType($block['type']) . ' : Invalid XML</strong></div>';
		}
	}
	
	private $benchTimes = null;
	
	private function addBenchTime($key)
	{
		if ($this->benchTimes !== null)
		{
			$current = microtime(true);
			if (isset($this->benchTimes[$key]))
			{
				$this->benchTimes[$key] += ($current - $this->benchTimes['c']);
			}
			else
			{
				$this->benchTimes[$key] = ($current - $this->benchTimes['c']);
			}
			$this->benchTimes['c'] = $current;
		}
	}
	
	/**
	 * @param website_persistentdocument_page $page
	 */
	public function render($page)
	{
		$dcs = f_DataCacheService::getInstance();
		$cacheItem = null;
		$putInCache = false;
		
		if (Framework::inDevelopmentMode() || $page->isNew() || $page->isPropertyModified('content'))
		{
			if (Framework::isDebugEnabled())
			{
				$current = microtime(true);
				$this->benchTimes = array('renderStart' => $current, 'c' => $current);
			}
		}
		else if ($dcs->isEnabled())
		{
			$cacheItem = $dcs->readFromCache(__METHOD__, array($page->getId(), RequestContext::getInstance()->getLang()), array(
				$page->getId(), 'modules_theme/pagetemplate'));
			$putInCache = true;
		}
		
		if ($cacheItem !== null && $cacheItem->isValid())
		{
			$cachedData = $cacheItem->getValue('blocksAndHtmlBody');
			$pageRenderInfo = unserialize($cachedData);
			$blocks = $pageRenderInfo['blocks'];
			$pageContent = f_util_DOMUtils::fromString($pageRenderInfo['htmlBody']);
			$pageContent->preserveWhiteSpace = false;
			$docType = $pageRenderInfo['docType'];
			
			$controller = website_BlockController::getInstance();
			$controller->setPage($page);
			
			$pageContext = $controller->getContext();
			$this->addFavIconInfo($pageContext);
			$pageContext->addContainerStylesheet();
		}
		else
		{
			$wsprs = website_PageRessourceService::getInstance();
			$docType = $wsprs->getPageDocType($page);
			$pageContent = $wsprs->getPagetemplateAsDOMDocument($page);
			
			$this->addBenchTime('templateFill');
			
			$blocks = $this->generateBlocks($pageContent);
			$this->addBenchTime('blocksParsing');
			
			$wsprs->buildBlockContainerForFrontOffice($pageContent, $blocks);
			$this->addBenchTime('blocksContainerGenerating');
			$pageContent->preserveWhiteSpace = false;
			
			$controller = website_BlockController::getInstance();
			$controller->setPage($page);
			
			$pageContext = $controller->getContext();
			$this->addFavIconInfo($pageContext);
			$pageContext->addContainerStylesheet();
			
			$this->addBenchTime('pageContextInitialize');
			if ($putInCache)
			{
				$htmlBody = $pageContent->saveXML($pageContent->documentElement);
				$cacheItem->setTTL(86400);
				$cacheItem->setValue("blocksAndHtmlBody", serialize(array("blocks" => $blocks, "htmlBody" => $htmlBody, 
					"docType" => $docType)));
				$dcs->writeToCache($cacheItem);
			}
		}
		
		$pageContext->setDoctype($docType);
		if (!$page->isNew())
		{
			$pageContext->addBlockMeta('page.label', $page->getLabel());
			$pageContext->addBlockMeta('page.navigationLabel', $page->getNavigationLabel());
				
			$d = DocumentHelper::getDocumentInstanceIfExists($pageContext->getDetailDocumentId());
			$pageContext->addBlockMeta('detail.navigationLabel', ($d) ? $d->getNavigationLabel() : '');
			$ws = website_WebsiteService::getInstance()->getCurrentWebsite();
			$pageContext->addBlockMeta('website.label', $ws->isNew() ? '' : $ws->getLabel());
			$p = $pageContext->getParent();
			$pageContext->addBlockMeta('topic.label', ($p instanceof website_persistentdocument_topic) ? $p->getLabel() : '');
		}
		$this->populateHTMLBlocks($controller, $blocks);
		$this->addBenchTime('blocksGenerating');
		
		$strFrom = array();
		$strTo = array();
		
		foreach ($blocks as $blockId => $block)
		{
			$list = $pageContent->getElementsByTagName('htmlblock_' . $blockId);
			if ($list->length == 1)
			{
				$strFrom[] = '<htmlblock_' . $blockId . '/>';
				$node = $list->item(0);
				if (f_util_StringUtils::isEmpty($block['html']))
				{
					$container = $node->parentNode;
					$container->setAttribute('class', 'empty-' . $container->getAttribute('class'));
					$strTo[] = '';
				}
				else
				{
					$strTo[] = $block['html'];
				}
			}
		}
		$htmlBody = $pageContent->saveXML($pageContent->documentElement);
		$htmlBody = str_replace($strFrom, $strTo, $htmlBody);
		
		$this->addBenchTime('htmlGenerating');
		$pageContext->benchTimes = $this->benchTimes;
		$pageContext->renderHTMLBody($htmlBody, website_PageRessourceService::getInstance()->getGlobalTemplate());
	}
	
	/**
	 * @param website_persistentdocument_page $page
	 * @param string[] $blockIdArray
	 */
	public function getRenderedBlock($page, $blockIdArray = array())
	{
		$wsprs = website_PageRessourceService::getInstance();
		$pageContent = $wsprs->getPagetemplateAsDOMDocument($page);
		if (is_array($blockIdArray) && count($blockIdArray))
		{
			$blocks = array();
			foreach ($this->generateBlocks($pageContent) as $blockId => $block)
			{
				$key = isset($block['id']) ? $block['id'] : 'b_' . $blockId;
				if (in_array($key, $blockIdArray))
				{
					$blocks[$blockId] = $block;
				}
			}
		}
		else
		{
			$blocks = $this->generateBlocks($pageContent);
		}
		
		$results = array();
		if (count($blocks))
		{
			$controller = website_BlockController::getInstance();
			$controller->setPage($page);
			$pageContext = $controller->getContext();
			$this->populateHTMLBlocks($controller, $blocks);
			$results = array();
			foreach ($blocks as $blockId => $block)
			{
				$key = isset($block['id']) ? $block['id'] : 'b_' . $blockId;
				$results[$key] = $block['html'];
			}
		}
		return $results;
	}
	
	/**
	 * @param website_Page $pageContext
	 */
	private function addFavIconInfo($pageContext)
	{
		$website = website_WebsiteService::getInstance()->getCurrentWebsite();
		if ($website && $website->getFavicon())
		{
			$favicon = $website->getFavicon();
			if ($favicon->isContextLangAvailable())
			{
				$info = $favicon->getInfo();
				$type = $info['extension'] == 'ico' ? 'image/x-icon' : $favicon->getMimetype();
				$url = $favicon->getDocumentService()->generateAbsoluteUrl($favicon, null, array());
			}
			else
			{
				RequestContext::getInstance()->beginI18nWork($favicon->getLang());
				$info = $favicon->getInfo();
				$type = $info['extension'] == 'ico' ? 'image/x-icon' : $favicon->getMimetype();
				$url = $favicon->getDocumentService()->generateAbsoluteUrl($favicon, null, array());
				RequestContext::getInstance()->endI18nWork();
			}
			
			$pageContext->addLink('icon', $type, $url);
			$pageContext->addLink('shortcut icon', $type, $url);
		}
	}
	
	/**
	 * Generate the compiled blocks for the given page.
	 * <changeblock type="modules_xxx_zzz" blockwidth="" editable="false" 
	 * 	[lang="fr"] [id=""] [marginRight=""] [flex=""] [__aaaaaa="value"]>[content]</changeblock>
	 * @param DOMDocument $DOMDocument
	 * @return array
	 */
	private function generateBlocks($DOMDocument)
	{
		$result = array();
		$blocks = $DOMDocument->getElementsByTagName('changeblock');
		$blockIndex = 0;
		foreach ($blocks as $block)
		{
			$blockIndex++;
			if (!$block->hasAttribute('type'))
			{
				continue;
			}
			$type = $block->getAttribute('type');
			$result[$blockIndex] = $this->buildBlockInfo($type, $this->parseBlockParameters($block), $block->getAttribute('lang'), $block->getAttribute('blockwidth'), $block->getAttribute('editable') != 'false', $block);
		}
		return $result;
	}
	
	public function buildBlockInfo($type, $parameters = array(), $lang = null, $blockwidth = null, $editable = true, $DomNode = null)
	{
		$blockInfos = array('type' => $type);
		$package = explode('_', $type);
		$packageName = $package[0] . '_' . $package[1];
		if ($lang)
		{
			$blockInfos['lang'] = $lang;
		}
		$blockInfos['package'] = $packageName;
		$blockInfos['name'] = $package[2];
		$blockInfos['editable'] = $editable;
		$blockInfos['blockwidth'] = $blockwidth;
		$blockInfos['parameters'] = $parameters;
		$class = str_replace('_', '-', $type);
		if (isset($blockInfos['parameters']['class']) && $class !== $blockInfos['parameters']['class'])
		{
			$class .= ' ' . $blockInfos['parameters']['class'];
		}
		$blockInfos['class'] = $class . ' ' . str_replace('_', '-', $packageName);
		
		$blockInfos['DomNode'] = $DomNode;
		
		if ($DomNode !== null)
		{
			if ($DomNode->hasAttribute("id"))
			{
				$blockInfos["id"] = $DomNode->getAttribute("id");
			}
			if ($DomNode->hasAttribute("marginRight"))
			{
				$blockInfos["marginRight"] = $DomNode->getAttribute("marginRight");
			}
			if ($DomNode->hasAttribute("flex"))
			{
				$blockInfos["flex"] = $DomNode->getAttribute("flex");
			}
		}
		
		return $blockInfos;
	}
	
	/**
	 * @param DOMElement $block
	 * @return array
	 */
	private function parseBlockParameters($block)
	{
		$parameters = array();
		foreach ($block->attributes as $attrName => $attrNode)
		{
			if (substr($attrName, 0, 2) === '__')
			{
				$parameters[substr($attrName, 2)] = $attrNode->nodeValue;
			}
		}
		$content = $block->textContent;
		if ($content)
		{
			$parameters['content'] = $content;
			while ($block->childNodes->item(0) !== null)
			{
				$block->removeChild($block->childNodes->item(0));
			}
		}
		if ($block->hasAttributeNS("*", "blockId"))
		{
			$parameters['blockId'] = $block->getAttributeNS("*", "blockId");
		}
		return $parameters;
	}
	
	/**
	 * @param website_BlockController $controller
	 * @param unknown_type $blocks
	 */
	private function populateHTMLBlocks($controller, &$blocks)
	{
		$blockPriorities = array();
		$bench = $this->benchTimes !== null;
		if ($bench)
		{
			$this->benchTimes['blocks'] = array();
		}
		$traceBlockAction = Framework::inDevelopmentMode() && Framework::isDebugEnabled();
		$bs = block_BlockService::getInstance();
		$nbBlocks = count($blocks);
		foreach ($blocks as $blockId => &$block)
		{
			$blocType = $block['type'];
			$className = $bs->getBlockActionClassNameByType($block['type']);
			if ($className === null)
			{
				$originalClassName = $className;
				$blocType = 'modules_website_Missing';
				$className = $bs->getBlockActionClassNameByType($blocType);
			}
			
			$reflectionClass = new ReflectionClass($className);
			if ($traceBlockAction)
			{
				$block['file'] = $reflectionClass->getFileName();
			}
			$classInstance = $reflectionClass->newInstance($blocType);
			if (isset($block['lang']))
			{
				$classInstance->setLang($block['lang']);
			}
			if ($blocType == 'modules_website_Missing')
			{
				$classInstance->setOriginalClassName($originalClassName);
			}
			
			foreach ($block['parameters'] as $name => $value)
			{
				$classInstance->setConfigurationParameter($name, $value);
			}
			$idPName = isset($block['id']) ? $block['id'] : 'b_' . $blockId;
			
			// This parameter can be used to identify this block inside the page.
			$classInstance->setConfigurationParameter(website_BlockAction::BLOCK_ID_PARAMETER_NAME, $idPName);
			
			$block['blockaction'] = $classInstance;
			$blockPriorities[$blockId] = ($nbBlocks - $blockId) + ($classInstance->getOrder() * 10);
		}
		
		asort($blockPriorities);
		$blockPriorities = array_reverse($blockPriorities, true);
		$httpRequest = change_Controller::getInstance()->getRequest();
		$traceBlockAction = Framework::inDevelopmentMode() && Framework::isDebugEnabled();
		foreach (array_keys($blockPriorities) as $blockId)
		{
			if ($bench)
			{
				$start = microtime(true);
			}
			
			$blockData = &$blocks[$blockId];
			$html = ($traceBlockAction) ? "<!-- Generated by " . $blockData['file'] . " -->" : "";
			$blockInstance = $blockData['blockaction'];
			
			// Begin capturing. TODO: make a dedicated method instead of write()
			$controller->getResponse()->getWriter()->write("");
			try
			{
				$controller->process($blockInstance, $httpRequest);
				$html .= $controller->getResponse()->getWriter()->getContent();
			}
			catch (TemplateNotFoundException $e)
			{
				Framework::exception($e);
				$html .= $e->getMessage();
			}
			
			$blockData['html'] = $html;
			
			if ($bench)
			{
				if (isset($blocks[$blockId]['id']))
				{
					$benchId = $blocks[$blockId]['id'];
				}
				else
				{
					$benchId = 'b_' . $blockId;
				}
				$this->benchTimes['blocks'][$benchId]['rendering'] = microtime(true) - $start;
			}
			
			unset($blocks[$blockId]['blockaction']);
		}
	}
	
	/**
	 * @param array $specs
	 * @return string
	 */
	private function getBlockClassNameForSpecs($specs)
	{
		return substr($specs['package'], 8) . '_Block' . ucfirst($specs['name']) . 'Action';
	}
	
	/**
	 * @param website_persistentdocument_page $document or null
	 * @param integer $websiteId
	 * @return array
	 */
	public function getReplacementsForTweet($document, $websiteId)
	{
		$label = array('name' => 'label', 'label' => LocaleService::getInstance()->trans('m.website.document.page.label', array('ucf')), 
			'maxLength' => 80);
		$shortUrl = array('name' => 'shortUrl', 'label' => LocaleService::getInstance()->trans('m.twitterconnect.bo.general.short-url', array('ucf')), 
			'maxLength' => 30);
		if ($document !== null)
		{
			$label['value'] = f_util_StringUtils::shortenString($document->getLabel(), 80);
			$shortUrl['value'] = website_ShortenUrlService::getInstance()->shortenUrl(LinkHelper::getDocumentUrl($document));
		}
		return array($label, $shortUrl);
	}
	
	/**
	 * @param website_persistentdocument_website $website
	 * @param string $lang
	 * @param string $modelName
	 * @param integer $offset
	 * @param integer $chunkSize
	 * @return website_persistentdocument_page[]
	 */
	public function getDocumentForSitemap($website, $lang, $modelName, $offset, $chunkSize)
	{
		return $this->getPersistentProvider()->createQuery($modelName, false)->add(Restrictions::published())->add(Restrictions::descendentOf($website->getId()))->add(Restrictions::ne('navigationVisibility', website_ModuleService::HIDDEN))->addOrder(Order::asc('id'))->setMaxResults($chunkSize)->setFirstResult($offset)->find();
	}
	
	/**
	 * @param website_persistentdocument_menuitemfunction $document
	 * @param array<string, string> $attributes
	 * @param integer $mode
	 * @param string $moduleName
	 */
	public function completeBOAttributes($document, &$attributes, $mode, $moduleName)
	{
		if ($document->getIsHomePage())
		{
			$attributes['icon'] = 'page-home';
			$attributes['isHomePage'] = true;
		}
		elseif ($document->getIsIndexPage())
		{
			$attributes['icon'] = 'page-index';
			$attributes['isIndexPage'] = true;
		}
		
		if (!($document instanceof website_persistentdocument_pagereference) && !($document instanceof website_persistentdocument_pageversion))
		{
			$countRef = website_PagereferenceService::getInstance()->getCountPagesReferenceByPage($document);
			if ($countRef > 0)
			{
				$attributes['hasPageRef'] = true;
			}
		}
	}
	
	/**
	 * @param website_persistentdocument_page $document
	 * @param string[] $propertiesNames
	 * @param array $formProperties
	 * @param integer $parentId
	 */
	public function addFormProperties($document, $propertiesNames, &$formProperties, $parentId = null)
	{
		$metainfos = $this->getBlockMetaInfos($document);
		$ls = LocaleService::getInstance();
		$jsonMeta = array();
		foreach ($metainfos as $zone => $metas)
		{
			$jsonMeta[$zone] = array();
			if ($zone === 'title')
			{
				$jsonMeta[$zone][] = array("value" => '{page.label}', "label" => $ls->trans("m.website.bo.blocks.metas-page-label"));
				$jsonMeta[$zone][] = array("value" => '{page.navigationLabel}', "label" => $ls->trans("m.website.bo.blocks.metas-page-navigationlabel"));
				$jsonMeta[$zone][] = array("value" => '{detail.navigationLabel}', "label" => $ls->trans("m.website.bo.blocks.metas-detail-navigationlabel"));
				$jsonMeta[$zone][] = array("value" => '{website.label}', "label" => $ls->trans("m.website.bo.blocks.metas-website-label"));
				$jsonMeta[$zone][] = array("value" => '{topic.label}', "label" => $ls->trans("m.website.bo.blocks.metas-topic-label"));
			}
			foreach ($metas as $meta)
			{
				$dummyInfo1 = explode(".", $meta);
				$shortMetaName = $dummyInfo1[1];
				$dummyInfo2 = explode("_", $dummyInfo1[0]);
				$moduleName = $dummyInfo2[0];
				$blockName = $dummyInfo2[1];
				$label = $ls->trans("m.$moduleName.bo.blocks.$blockName.metas.$shortMetaName");
				$jsonMeta[$zone][] = array("value" => "{" . $meta . "}", "label" => $label);
			}
		}
		$formProperties["metainfo"] = $jsonMeta;
	}
	
	/**
	 * @param indexer_IndexedDocument $indexedDocument
	 * @param website_persistentdocument_page $document
	 * @param indexer_IndexService $indexService
	 */
	protected function updateIndexDocument($indexedDocument, $document, $indexService)
	{
		if ($document->getIndexingstatus() == false)
		{
			$indexedDocument->foIndexable(false);
		}
		$indexedDocument->setLabel($document->getNavigationLabel());
		$indexedDocument->setText($this->getFullTextContent($document));
	}
	
	/**
	 * @param website_persistentdocument_page $document
	 * @return website_MenuEntry|null
	 */
	public function getMenuEntry($document)
	{
		$visibility = $document->getNavigationVisibility();
		if ($visibility == website_ModuleService::HIDDEN || $visibility == website_ModuleService::HIDDEN_IN_MENU_ONLY)
		{
			return null;
		}
		return $this->doGetMenuEntry($document);
	}
	
	/**
	 * @param website_persistentdocument_page $document
	 * @return website_MenuEntry|null
	 */
	public function getSitemapEntry($document)
	{
		$visibility = $document->getNavigationVisibility();
		if ($visibility == website_ModuleService::HIDDEN || $visibility == website_ModuleService::HIDDEN_IN_SITEMAP_ONLY)
		{
			return null;
		}
		return $this->doGetMenuEntry($document);
	}
	
	/**
	 * @param website_persistentdocument_page $document
	 * @return website_MenuEntry|null
	 */
	protected function doGetMenuEntry($document)
	{
		$entry = website_MenuEntry::getNewInstance();
		$entry->setDocument($document);
		$entry->setLabel($document->getNavigationLabel());
		$entry->setUrl(LinkHelper::getDocumentUrl($document));
		if ($document->getId() == website_PageService::getInstance()->getCurrentPageId())
		{
			$entry->setCurrent(true);
		}
		return $entry;
	}
}