<?php
class website_PageChildrenFinder
{

	private $navigationOnly = false;

	public function setNavigationOnly($navigationOnly)
	{
		$this->navigationOnly = $navigationOnly;
	}

	protected function hasPermission($parentDocument)
	{
		$us = users_UserService::getInstance();
		$ps = change_PermissionService::getInstance();

		$foUser = $us->getCurrentFrontEndUser();
		if ($foUser !== null && $ps->hasPermission($foUser, 'modules_website.List.topic', $parentDocument->getId()))
		{
			return true;
		}
		$boUser = $us->getCurrentBackEndUser();
		if ($boUser !== null && $ps->hasPermission($boUser, 'modules_website.List.topic', $parentDocument->getId()))
		{
			return true;
		}

		return false;
	}


	/**
	 * @param f_persistentdocument_PersistentDocument $parentDocument
	 * @return website_tree_NodeList
	 */
	public function getChildren($parentDocument)
	{
		$nodeList = new website_tree_NodeList();
		if (!$this->hasPermission($parentDocument) || $parentDocument instanceof website_persistentdocument_page)
		{
			return $nodeList;	
		}
		 
		if ($parentDocument instanceof generic_persistentdocument_folder)
		{
			$documents = website_WebsiteService::getInstance()->createQuery()->add(Restrictions::published())->find();
		}
		else
		{
			$documents = website_TopicService::getInstance()->createQuery()
			->add(Restrictions::published())
			->add(Restrictions::childOf($parentDocument->getId()))
			->find();
		}
		foreach ($documents as $document)
		{
			$n = $this->buildNavigationNode($document);
			if ($n !== NULL)
			{
				$nodeList->append($n);
			}
		}

		if ($this->navigationOnly)
		{
			return $nodeList;
		}

		$documents = website_PageService::getInstance()->createQuery()
		->add(Restrictions::published())
		->add(Restrictions::childOf($parentDocument->getId()))
		->find();

		foreach ($documents as $document)
		{
			$n = $this->buildDataNode($document);
			if ($n !== NULL)
			{
				$nodeList->append($n);
			}
		}

		return $nodeList;
	}

	/**
	 * @param f_persistentdocument_PersistentDocument $document
	 */
	protected function buildNavigationNode($document)
	{
		$attributes = array();
		$attributes['label'] = $document->getLabel();
		return new website_tree_Node($document->getId(), $attributes, true);
	}

	/**
	 * @param f_persistentdocument_PersistentDocument $document
	 */
	protected function buildDataNode($document)
	{
		$attributes = array();
		$attributes['label'] = $document->getLabel();
		$attributes['filename'] = $document->getLabel() . '.html';
		$attributes['title'] = $document->getMetatitle();

		$attributes['url'] = LinkHelper::getDocumentUrl($document);
		$attributes['extension'] = 'html';
		$attributes['size'] = ' ';
		return new website_tree_Node($document->getId(), $attributes, false);
	}
}
