<?php
/**
 * @date Mon, 09 Jul 2007 10:11:58 +0200
 * @author inthause
 */
class website_PagereferenceService extends website_PageService
{
	/**
	 * @var website_PagereferenceService
	 */
	private static $instance;

	/**
	 * @return website_PagereferenceService
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
	 * @return website_persistentdocument_pagereference
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_website/pagereference');
	}

	/**
	 * Create a query based on 'modules_website/pagereference' model
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->pp->createQuery('modules_website/pagereference');
	}

	/**
	 * @param website_persistentdocument_pagereference $pageReference
	 * @param website_persistentdocument_page $page
	 * @param Integer $topicId
	 */
	public function updatePageReference($pageReference, $page, $topicId)
	{
		$requestContext = RequestContext::getInstance();
		$vo = $page->getLang();

		$pageReference->setReferenceofid($page->getId());
		try
		{
			$this->tm->beginTransaction();
			//Update VO
			try
			{
				$requestContext->beginI18nWork($vo);
				if ($pageReference->hasMeta('f_tags'))
				{
					$existingTags = $pageReference->getMeta('f_tags');
				}
				else 
				{
					$existingTags = null;
				}
				$page->copyPropertiesTo($pageReference, true);
				// TODO: use TagService::clearTagsMeta()
				$pageReference->setMeta("f_tags", $existingTags);
				$requestContext->endI18nWork();
			}
			catch (Exception $e)
			{
				$requestContext->endI18nWork($e);
			}

			//Update localized
			foreach ($requestContext->getSupportedLanguages() as $lang)
			{
				if ($lang == $vo)
				{
					continue;
				}

				try
				{
					$requestContext->beginI18nWork($lang);
					if ($page->isContextLangAvailable())
					{
						$page->copyPropertiesTo($pageReference, false);
					}
					$requestContext->endI18nWork();
				}
				catch (Exception $e)
				{
					$requestContext->endI18nWork($e);
				}
			}

			if ($pageReference->getIsIndexPage())
			{
				$rubrique = DocumentHelper::getDocumentInstance($topicId);
				if ($rubrique instanceof website_persistentdocument_topic)
				{
					$oldIndexPage = $rubrique->getIndexPage();
					if ($oldIndexPage != null && !($oldIndexPage instanceof website_persistentdocument_pagereference))
					{
						if (Framework::isDebugEnabled())
						{
							Framework::debug(__METHOD__ . ' page  ' . $oldIndexPage->__toString() . ' is index page of ' . $rubrique->__toString() . ' -> Index not overrided by ' . $pageReference->__toString());
						}
						$pageReference->setIsIndexPage(false);
					}
				}
			}

			try 
			{
				$requestContext->beginI18nWork($vo);
				$this->save($pageReference, $topicId);
				$requestContext->endI18nWork();
			}
			catch (Exception $e)
			{
				$requestContext->endI18nWork($e);
			}
			
			//Update TopicIndex
			if ($pageReference->getIsIndexPage())
			{
				$topic = DocumentHelper::getDocumentInstance($topicId);
				website_TopicService::getInstance()->setIndexPage($topic, $pageReference);
			}

			//Update tag
			$this->updateTags($pageReference, $page);
			$this->tm->commit();

		}
		catch (Exception $e)
		{
			$this->tm->rollBack($e);
		}
	}

	/**
	 * Synchronisation des tags de $pageReference en fonction des tags de $page
	 * @param website_persistentdocument_pagereference $pageReference
	 * @param website_persistentdocument_page $page
	 */
	private function updateTags($pageReference, $page)
	{
		$tagService = TagService::getInstance();

		$pageTags = $tagService->getTags($page);
		$refTags = $tagService->getTags($pageReference);
		
		//Framework::debug(__METHOD__." ".$pageReference->getId()." ".$page->getId()." ".var_export($refTags, true)." ".var_export($pageTags, true)." ".ProcessUtils::getBackTrace());

		//Ajout des tags manquants
		foreach ($pageTags as $tag)
		{
			if ($tagService->isFunctionalTag($tag) && array_search($tag, $refTags) === false)
			{
				$tagService->addTag($pageReference, $tag);
			}
		}

		foreach ($refTags as $tag)
		{
			if (array_search($tag, $pageTags) === false)
			{
				//Ne pas envoyer les evenements on passe par le provider
				$tagService->removeTag($pageReference, $tag);
			}
		}
	}

	/**
	 * @param website_persistentdocument_pagereference $document
	 * @param String $tag
	 * @return void
	 */
	public function tagAdded($document, $tag)
	{
		return;
	}

	/**
	 * @param website_persistentdocument_pagereference $document
	 * @param String $tag
	 * @return void
	 */
	public function tagRemoved($document, $tag)
	{
		return;
	}

	/**
	 * @param website_persistentdocument_pagereference $fromDocument
	 * @param website_persistentdocument_pagereference $toDocument
	 * @param String $tag
	 * @return void
	 */
	public function tagMovedTo($fromDocument, $toDocument, $tag)
	{
		return;
	}

	/**
	 * @param website_persistentdocument_pagereference $newDocument
	 * @param website_persistentdocument_pagereference $originalDocument
	 * @param Integer $parentNodeId
	 */
	protected function preDuplicate($newDocument, $originalDocument, $parentNodeId)
	{
		throw new IllegalOperationException('This document cannot be duplicated.');
	}
	
	/**
	 * @param website_persistentdocument_page $document
	 * @param integer $parentNodeId
	 */
	protected function postInsert($document, $parentNodeId = null)
	{
		// THIS HAS TO BE EMPTY!
	}
	
	/**
	 * @param website_persistentdocument_pagereference $document
	 */
	public function deleteAll($document)
	{
		$requestContext = RequestContext::getInstance();
		$vo = $document->getLang();
		
		foreach ($requestContext->getSupportedLanguages() as $lang)
		{
			if ($lang == $vo)
			{
				continue;
			}
			
			try
			{
				$requestContext->beginI18nWork($lang);
				if ($document->isContextLangAvailable())
				{
					$this->delete($document);
				}
				$requestContext->endI18nWork();
			}
			catch (Exception $e)
			{
				$requestContext->endI18nWork($e);
			}			
		}
		
		try
		{
			$requestContext->beginI18nWork($vo);
			$this->delete($document);
			$requestContext->endI18nWork();
		}
		catch (Exception $e)
		{
			$requestContext->endI18nWork($e);
		}
	}
	
	/**
	 * @param website_persistentdocument_pagereference $pageReference
	 * @param website_persistentdocument_page
	 */
	public function getPageByPageReference($pageReference)
	{
	   return DocumentHelper::getDocumentInstance($pageReference->getReferenceofid());
	    
	}
	
	/**
	 * @param website_persistentdocument_page $page
	 * @return array<website_persistentdocument_pagereference>
	 */	
	public function getPagesReferenceByPage($page)
	{
		$query = $this->createQuery()
						->add(Restrictions::eq('referenceofid', $page->getId()));				
		return $query->find();
	}
	
	/**
	 * @param website_persistentdocument_pagereference $pageReference
	 * @param Boolean $isIndexPage
	 * @param Boolean $userSetting
	 */
	public function setIsIndexPage($pageReference, $isIndexPage, $userSetting = false)
	{
		try
		{
			$this->tm->beginTransaction();
			$pageReference->setIsIndexPage($isIndexPage); 
			$pageReference->setIndexpagedefined($userSetting && $isIndexPage);			    
						
	    	if ($pageReference->isModified())
			{
				$this->pp->updateDocument($pageReference);
			}
			$this->tm->commit();
		}
		catch (Exception $e)
		{
			$this->tm->rollBack($e);
		}
	}	
	
	/**
	 * @param website_persistentdocument_pagereference $document
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
				website_TopicService::getInstance()->publishDocumentIfPossible($parentDocument, array('childrenPublicationStatusChanged' => $document));
			}
		}
	}
}