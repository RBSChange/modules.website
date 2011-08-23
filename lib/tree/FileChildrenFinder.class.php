<?php
class website_FileChildrenFinder
{

	private $navigationOnly = false;

	protected $imageOnly = false;

	public function setNavigationOnly($navigationOnly)
	{
		$this->navigationOnly = $navigationOnly;
	}

	function fileUpload($fileName, $serverFilePath, $parentId, $fileAlt)
	{
		if (!$this->hasPermission($parentId, 'modules_media.Insert.media'))
		{
			return null;
		}
		$destination = DocumentHelper::getDocumentInstance($parentId);
		if ($destination instanceof generic_persistentdocument_folder)
		{
			return MediaHelper::addUploadedFile($fileName, $serverFilePath, $destination, $fileAlt);
		}
		return null;
	}

	public function createFolder($parentId, $name)
	{
		if (!$this->hasPermission($parentId, 'modules_media.Insert.folder'))
		{
			return null;
		}
		$destination = DocumentHelper::getDocumentInstance($parentId);
		if ($destination instanceof generic_persistentdocument_folder)
		{
			$fs = generic_FolderService::getInstance();
			$folder = $fs->getNewDocumentInstance();
			$folder->setLabel($name);
			$fs->save($folder, $parentId);
			return $folder;
		}
		return null;
	}

	protected function hasPermission($parentDocumentId)
	{
		return $this->_hasPermission($parentDocumentId, 'modules_media.List.folder');
	}

	private function _hasPermission($parentDocumentId, $perm)
	{
		$us = users_UserService::getInstance();
		$ps = change_PermissionService::getInstance();

		$foUser = $us->getCurrentFrontEndUser();
		if ($foUser !== null && $ps->hasPermission($foUser, $perm, $parentDocumentId))
		{
			return true;
		}
		$boUser = $us->getCurrentBackEndUser();
		if ($boUser !== null && $ps->hasPermission($boUser, $perm, $parentDocumentId))
		{
			return true;
		}

		return false;
	}

	/**
	 * @param f_persistentdocument_PersistentDocument $document
	 * @return website_tree_NodeList
	 */
	public function getChildren($parentDocument)
	{
		$nodeList = new website_tree_NodeList();
		if (!$this->hasPermission($parentDocument->getId()))
		{
			return $nodeList;
		}

		if ($parentDocument instanceof media_persistentdocument_file)
		{
			return $nodeList;
		}

		$documents = generic_FolderService::getInstance()->createQuery()->add(Restrictions::published())->add(Restrictions::childOf($parentDocument->getId()))->find();

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

		$query = media_MediaService::getInstance()->createQuery()
		->add(Restrictions::published())
		->add(Restrictions::childOf($parentDocument->getId()));
			
		if ($this->imageOnly)
		{
			$query->add(Restrictions::eq('mediatype', 'image'));
		}

		$documents = $query	->find();

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
		$attributes['filename'] = $document->getFilename();
		$attributes['title'] = $document->getTitle();

		$attributes['url'] = LinkHelper::getDocumentUrl($document);
		$attributes['extension'] = f_util_FileUtils::getFileExtension($document->getFilename());
		$KbSize = intval($document->getContentlength() / 1024);
		$attributes['size'] = $KbSize > 0 ? $KbSize : 1;
		return new website_tree_Node($document->getId(), $attributes, false);
	}
}

class website_ImageChildrenFinder extends website_FileChildrenFinder
{
	/**
	 * @param f_persistentdocument_PersistentDocument $document
	 * @return website_tree_NodeList
	 */
	public function getChildren($parentDocument)
	{
		$this->imageOnly = true;
		return parent::getChildren($parentDocument);
	}
}
