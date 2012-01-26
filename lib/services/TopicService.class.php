<?php
/**
 * @date Wed Feb 28 12:25:05 CET 2007
 * @author INTbonjF
 */
class website_TopicService extends f_persistentdocument_DocumentService
{
	/**
	 * @var website_TopicService
	 */
	private static $instance;

	/**
	 * @return website_TopicService
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
	 * @return website_persistentdocument_topic
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_website/topic');
	}

	/**
	 * Create a query based on 'modules_website/topic' model
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->pp->createQuery('modules_website/topic');
	}

	/**
	 * @param website_persistentdocument_topic $document
	 */
	public function isPublishable($document)
	{
		if (!parent::isPublishable($document))
		{
			return false;
		}
		
		$node = TreeService::getInstance()->getInstanceByDocument($document);
		if ($node && $node->hasChildren())
		{
			if ($this->hasPublishedPages($document))
			{
				return true;
			}
			else if ($this->hasPublishedTopics($document)) 
			{
				return true;
			}
		}
		$this->setActivePublicationStatusInfo($document, '&modules.website.document.systemtopic.publication.no-published-page-or-subtopic;');
		return false;
	}
	
	/**
	 * @param website_persistentdocument_topic $document
	 * @return Boolean
	 */
	public final function hasPublishedTopics($document)
	{
		$rows = website_TopicService::getInstance()->createQuery()
			->add(Restrictions::published())
			->add(Restrictions::childOf($document->getId()))
			->setProjection(Projections::property('id'))
			->setMaxResults(1)
			->find();
		return (count($rows) === 1); 
	}
	
	/**
	 * @param website_persistentdocument_topic $document
	 * @return Boolean
	 */
	public final function hasPublishedPages($document)
	{
		$rows = website_PageService::getInstance()->createQuery()
			->add(Restrictions::published())
			->add(Restrictions::childOf($document->getId()))
			->setProjection(Projections::property('id'))
			->setMaxResults(1)
			->find();
		if (count($rows) === 1)
		{
			return true;
		}
		
		$rows = website_PagereferenceService::getInstance()->createQuery()
			->add(Restrictions::published())
			->add(Restrictions::childOf($document->getId()))
			->setProjection(Projections::property('id'))
			->setMaxResults(1)
			->find();
		return (count($rows) === 1); 
	}

	/**
	 * @param website_persistentdocument_topic $document
	 * @param String $oldPublicationStatus
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
				$this->publishDocumentIfPossible($parentDocument, array('childrenPublicationStatusChanged' => $document));
			}
		}
		
	}
	
	/**
	 * @see f_persistentdocument_DocumentService::getWebsiteId()
	 *
	 * @param website_persistentdocument_topic $document
	 * @return integer
	 */
	public function getWebsiteId($document)
	{
		return $document->getMeta("websiteId");
	}
	
	
	/**
	 * @param website_persistentdocument_topic $document
	 * @param integer $parentNodeId
	 */
	protected function preInsert($document, $parentNodeId = null)
	{
		website_WebsiteModuleService::getInstance()->setWebsiteMetaFromParentId($document, $parentNodeId);
	}

	/**
	 * @param website_persistentdocument_topic $document
	 */
	protected function preDelete($document)
	{
		//remove pagereference generated automaticly
		$children = $this->getChildrenOf($document);
		$pageRefChildren = $this->getChildrenOf($document, 'modules_website/pagereference');
		$pagereferenceService = website_PagereferenceService::getInstance();
		if (count($pageRefChildren) != 0 && count($children) == count($pageRefChildren))
		{
			foreach ($pageRefChildren as $pageReference)
			{
				$pagereferenceService->deleteAll($pageReference);
			}
		}
	}

	/**
	 * @param website_persistentdocument_topic $document
	 * @param Integer $parentNodeId Parent node ID where to save the document (optionnal).
	 * @return void
	 */
	protected function postInsert($document, $parentNodeId = null)
	{
		if (!is_null($parentNodeId))
		{
			$this->synchronizeFunctionalPage($document);
		}
	}

	/**
	 * @param website_persistentdocument_topic $document
	 * @param Integer $parentNodeId
	 */
	protected function postSave($document, $parentNodeId)
	{
		if ($document->isPropertyModified('label'))
		{
			website_MenuitemdocumentService::getInstance()->synchronizeLabelForRelatedMenuItems($document);
		}
	}
	
	/**
	 * @param website_persistentdocument_topic $document
	 */
	protected function postDeleteLocalized($document)
	{
		website_MenuitemdocumentService::getInstance()->removeTranslationForRelatedMenuItems($document);
	}
	
	/**
	 * @param website_persistentdocument_topic $topic
	 */
	private function synchronizeFunctionalPage($topic)
	{
		if (Framework::isDebugEnabled())
		{
			Framework::debug(__METHOD__.'('.$topic->__toString().')');
		}
		$topicNode = TreeService::getInstance()->getInstanceByDocument($topic);
		$parentNode = $topicNode->getParent();

		$query = $this->pp->createQuery('modules_website/page')
		->add(Restrictions::childOf($parentNode->getId()));
		$pages = $query->find();

		$tagService = TagService::getInstance();
		$pageService = website_PageService::getInstance();
		foreach ($pages as $page)
		{
			$tags = $tagService->getTags($page);
			foreach ($tags as $tag)
			{
				if ($tagService->isFunctionalTag($tag))
				{
					$pageRef = $pageService->createPageReference($topic, $page);
					if ($topic->getIndexPage() === null)
					{
						$basePage = $this->getDocumentInstance($pageRef->getReferenceofid());
						if ($basePage->getIsIndexPageForSubTopics())
						{
							$this->setIndexPage($topic, $pageRef);
						}
					}
					break;
				}
			}
		}
	}

	/**
	 * @param website_persistentdocument_topic $document
	 * @param Integer $destId
	 */
	protected function onMoveToStart($document, $destId)
	{
		// We get the referenceofid's of the pagereferences we need to delete (those that were inherited from a parent topic)

		$refencesToDeleteIdQuery = $this->getPersistentProvider()->createQuery('modules_website/pagereference')
		->add(Restrictions::childOf($document->getId()))
		->setProjection(Projections::groupProperty('referenceofid'), Projections::property('referenceofid', 'id'));

		$ids = array();
		foreach ($refencesToDeleteIdQuery->find() as $value)
		{
			$ids[] = $value['id'];
		}

		if (count($ids) > 0)
		{
			// We delete all the references
			$this->getPersistentProvider()->createQuery('modules_website/pagereference')
			->add(Restrictions::descendentOf($document->getId()))
			->add(Restrictions::in('referenceofid', $ids))->delete();
		}
	}

	/**
	 * @param website_persistentdocument_topic $document
	 * @param Integer $destId
	 */
	protected function onDocumentMoved($document, $destId)
	{
		$destination = DocumentHelper::getDocumentInstance($destId);
		if ($destination instanceof generic_persistentdocument_rootfolder )
		{
			return;
		}
		// We synchronize the references in the moved topic and regenerate its topic cache
		$this->synchronizeFunctionalPage($document);

		// same for his descendents
		$subTopics = $this->createQuery()->add(Restrictions::descendentOf($document->getId()))->find();
		foreach ($subTopics as $topic)
		{
			$this->synchronizeFunctionalPage($topic);
		}

		// update websiteId meta if needed
		if ($destination instanceof website_persistentdocument_website)
		{
			$newWebsiteId = $destId;
		}
		else
		{
			$newWebsiteId = $destination->getMeta("websiteId");
		}
		if ($document->getMeta("websiteId") != $newWebsiteId)
		{
			$document->setMeta("websiteId", $newWebsiteId);
			$document->saveMeta();
				
			// update children meta too.
			// N.B : functionnal pages were just created so they are ok.
			$pages = $this->getPersistentProvider()->createQuery('modules_website/page')
			->add(Restrictions::descendentOf($document->getId()))->find();
				
			foreach ($pages as $page)
			{
				$page->setMeta("websiteId", $newWebsiteId);
				$page->saveMeta();
			}
		}
	}

	/**
	 * @param website_persistentdocument_topic $topic
	 * @param website_persistentdocument_page $newIndexPage
	 * @param Boolean $userSetting
	 */
	public function setIndexPage($topic, $newIndexPage, $userSetting = false)
	{
		if (!$topic instanceof website_persistentdocument_topic)
		{
			throw new IllegalArgumentException('topic', 'website_persistentdocument_topic');
		}
		try
		{
			$this->tm->beginTransaction();
			$oldPage = $topic->getIndexPage();
			if (!DocumentHelper::equals($oldPage, $newIndexPage))
			{
				if ($oldPage !== null)
				{
					$oldPage->getDocumentService()->setIsIndexPage($oldPage, false, $userSetting);
				}

				if ($newIndexPage !== null)
				{
					$newIndexPage->getDocumentService()->setIsIndexPage($newIndexPage, true, $userSetting);
				}

				$topic->setIndexPage($newIndexPage);
				$requestContext = RequestContext::getInstance();
				try
				{
					$requestContext->beginI18nWork($topic->getLang());
					$topic->save();
					$requestContext->endI18nWork();
				}
				catch (Exception $e)
				{
					$requestContext->endI18nWork($e);
				}
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
	 * @param website_persistentdocument_topic $document
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
	 * @param website_persistentdocument_topic $document
	 * @return website_persistentdocument_page
	 */
	public function getDisplayPage($document)
	{
		$model = $document->getPersistentModel();
		if ($model->hasURL() && $document->isPublished() && $document->getIndexPageId())
		{
			return $document->getIndexPage();
		}
		return null;
	}
	
	/**
	 * @param website_persistentdocument_topic $document
	 * @param string $moduleName
	 * @param string $treeType
	 * @param array<string, string> $nodeAttributes
	 */	
	public function addTreeAttributes($document, $moduleName, $treeType, &$nodeAttributes)
	{
	    $blocClass = $moduleName .'_BlockTopicAction';
	    if (f_util_ClassUtils::classExists($blocClass))
	    {
	        $nodeAttributes['block'] = 'modules_' . $moduleName . '_topic';
	    }
	    else
	    {
	        $nodeAttributes['block'] = '';
	    }
		if ($treeType == 'wlist')
		{
	    	$nodeAttributes['thumbnailsrc'] = MediaHelper::getIcon('topic');
		}
	}
	
	/**
	 * @param website_persistentdocument_topic $document
	 * @return website_MenuEntry|null
	 */
	public function getMenuEntry($document)
	{
		$visibility = $document->getNavigationVisibility();
		if ($visibility == WebsiteConstants::VISIBILITY_HIDDEN || $visibility == WebsiteConstants::VISIBILITY_HIDDEN_IN_MENU_ONLY)
		{
			return null;
		}
		return $this->doGetMenuEntry($document);
	}
	
	/**
	 * @param website_persistentdocument_topic $document
	 * @return website_MenuEntry|null
	 */
	public function getSitemapEntry($document)
	{
		$visibility = $document->getNavigationVisibility();
		if ($visibility == WebsiteConstants::VISIBILITY_HIDDEN || $visibility == WebsiteConstants::VISIBILITY_HIDDEN_IN_SITEMAP_ONLY)
		{
			return null;
		}
		return $this->doGetMenuEntry($document);
	}
	
	/**
	 * @param website_persistentdocument_topic $document
	 * @return website_MenuEntry|null
	 */
	protected function doGetMenuEntry($document)
	{
		$entry = website_MenuEntry::getNewInstance();
		$entry->setDocument($document);
		$entry->setLabel($document->getLabel());
		$entry->setVisual($document->getVisual());
		if ($document->hasPublishedIndexPage())
		{
			$entry->setUrl(LinkHelper::getDocumentUrl($document));
		}
		$entry->setContainer(true);
		return $entry;
	}
	
	/**
	 * @param website_persistentdocument_topic $document
	 * @return f_persistentdocument_PersistentDocument[]
	 */
	public function getChildrenDocumentsForMenu($document)
	{
		return $document->getDocumentService()->getPublishedChildrenOf($document);
	}
}