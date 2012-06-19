<?php
/**
 * @package modules.website
 * @method website_PagereferenceService getInstance()
 */
class website_PagereferenceService extends website_PageService
{
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
		return $this->getPersistentProvider()->createQuery('modules_website/pagereference');
	}
	
	/**
	 * @param website_persistentdocument_pagereference $pageReference
	 * @param website_persistentdocument_page $page
	 * @param integer $topicId
	 */
	public function updatePageReference($pageReference, $page, $topicId)
	{
		$exludedNames = array('id', 'model', 'lang', 'documentversion', 'referenceofid', 'metastring', 'isIndexPage', 
			'isHomePage');
		$propsNames = array();
		$i18nPropsNames = array();
		foreach ($page->getPersistentModel()->getPropertiesInfos() as $propertyInfos)
		{
			/* @var $propertyInfos propertyInfo */
			$name = $propertyInfos->getName();
			if (in_array($name, $exludedNames))
			{
				continue;
			}
			$propsNames[] = $name;
			if ($propertyInfos->isLocalized())
			{
				$i18nPropsNames[] = $name;
			}
			;
		}
		
		try
		{
			$this->getTransactionManager()->beginTransaction();
			
			$rc = RequestContext::getInstance();
			$useI18nSynchro = $rc->hasI18nSynchro();
			if ($useI18nSynchro)
			{
				$data = LocaleService::getInstance()->getI18nSynchroForDocument($page);
				$i18nSynchroStates = $data['states'];
			}
			
			$vo = $page->getLang();
			
			foreach ($page->getI18nInfo()->getLangs() as $lang)
			{
				try
				{
					$rc->beginI18nWork($lang);
					if ($useI18nSynchro && (!isset($i18nSynchroStates[$lang]) || $i18nSynchroStates[$lang]['status'] == LocaleService::SYNCHRO_SYNCHRONIZED))
					{
						continue;
					}
					
					if ($vo === $lang)
					{
						$pageReference->setReferenceofid($page->getId());
						$page->copyPropertiesListTo($pageReference, $propsNames, true);
					}
					else
					{
						$page->copyPropertiesListTo($pageReference, $i18nPropsNames, false);
					}
					
					$this->save($pageReference, $topicId);
					$rc->endI18nWork();
				}
				catch (Exception $e)
				{
					$rc->endI18nWork($e);
				}
			}
			
			$this->updateTags($pageReference, $page);
			$this->getTransactionManager()->commit();
		}
		catch (Exception $e)
		{
			$this->getTransactionManager()->rollBack($e);
		}
	}
	
	/**
	 * @param website_persistentdocument_page $page
	 */
	protected function synchronizeReferences($page)
	{
		return;
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

		// Add missing tags.
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
				// Ne pas envoyer les evenements on passe par le provider.
				$tagService->removeTag($pageReference, $tag);
			}
		}
	}
	
	/**
	 * @param website_persistentdocument_pagereference $document
	 * @param string $tag
	 * @return void
	 */
	public function tagAdded($document, $tag)
	{
		return;
	}
	
	/**
	 * @param website_persistentdocument_pagereference $document
	 * @param string $tag
	 * @return void
	 */
	public function tagRemoved($document, $tag)
	{
		return;
	}
	
	/**
	 * @param website_persistentdocument_pagereference $fromDocument
	 * @param website_persistentdocument_pagereference $toDocument
	 * @param string $tag
	 * @return void
	 */
	public function tagMovedTo($fromDocument, $toDocument, $tag)
	{
		return;
	}
	
	/**
	 * @param website_persistentdocument_pagereference $newDocument
	 * @param website_persistentdocument_pagereference $originalDocument
	 * @param integer $parentNodeId
	 */
	protected function preDuplicate($newDocument, $originalDocument, $parentNodeId)
	{
		throw new IllegalOperationException('This document cannot be duplicated.');
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
		$query = $this->createQuery()->add(Restrictions::eq('referenceofid', $page->getId()));
		return $query->find();
	}
	
	/**
	 * @param website_persistentdocument_page $page
	 * @return integer
	 */
	public function getCountPagesReferenceByPage($page)
	{
		$results = $this->createQuery()->add(Restrictions::eq('referenceofid', $page->getId()))
			->setProjection(Projections::rowCount('count'))->findColumn('count');
		return (count($results) == 1) ? $results[0] : 0;
	}
	
	/**
	 * @param website_persistentdocument_pagereference $document
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
	}
	
	/**
	 * @param website_persistentdocument_pagereference $document
	 * @param array<string, string> $attributes
	 * @param integer $mode
	 * @param string $moduleName
	 */
	public function completeBOAttributes($document, &$attributes, $mode, $moduleName)
	{
		parent::completeBOAttributes($document, $attributes, $mode, $moduleName);
		if ($document->getIsIndexPage())
		{
			$attributes['icon'] = 'page-reference-index';
		}
	}
	
	// Deprecated

	/**
	 * @deprecated use purgeDocument
	 */
	public function deleteAll($document)
	{
		$this->purgeDocument($document);
	}
}