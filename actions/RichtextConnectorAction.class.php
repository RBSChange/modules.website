<?php
class website_RichtextConnectorAction extends change_Action
{
	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	public function _execute($context, $request)
	{
		$command = $request->getParameter('Command');
		$resourceType = $request->getParameter('Type');
		$currentFolder = $request->getParameter('CurrentFolder');
		$moduleName = $this->convertRessourceTypeToModuleName($resourceType);
		$parentIds = $this->resolveCurrentFolder($moduleName, $request->getParameter('CurrentFolderKey'));	
		$currentFolderKey = implode('/', $parentIds) . '/';
		$request->setParameter('CurrentFolderKey', $currentFolderKey);
		$parentId = f_util_ArrayUtils::lastElement($parentIds);
		$request->setParameter('parentId', $parentId);
		
		// TODO: process commands on "DataProvider", using pattern "if method exists",
		// especially for createFolder & fileUpload 
		switch ($command)
		{
			case 'GetFoldersAndFiles' :
				header('Content-Type' . ':' . 'text/xml');
				$serializer = new website_tree_Serializer();
				$serializer->command = $command;
				$serializer->resourceType = $resourceType;
				$serializer->currentFolder = $currentFolder;
				$serializer->currentFolderKey = $currentFolderKey;
				echo $serializer->serialize($this->getNodeList($resourceType, $parentId));
				return change_View::NONE;
			
			case 'GetFolders' :
				header('Content-Type' . ':' . 'text/xml');
				$serializer = new website_tree_Serializer();
				$serializer->command = $command;
				$serializer->resourceType = $resourceType;
				$serializer->currentFolder = $currentFolder;
				$serializer->currentFolderKey = $currentFolderKey;
				echo $serializer->serialize($this->getNodeList($resourceType, $parentId, true));
				return change_View::NONE;
			
			case 'CreateFolder' :
				header('Content-Type' . ':' . 'text/xml');
				$serializer = new website_tree_Serializer();
				$serializer->command = $command;
				$serializer->resourceType = $resourceType;
				$serializer->currentFolder = $currentFolder;
				$serializer->currentFolderKey = $currentFolderKey;
				
				$newFolderName = $request->getParameter('NewFolderName');
				$folder = $this->createFolder($parentId, $newFolderName);
				if ($folder === null)
				{
					//Unknown error creating folder
					$serializer->error = '110';
				}
				echo $serializer->serialize(null);
				return change_View::NONE;
			
			case 'FileUpload' :
				//NewFile
				if ($this->fileUpload($parentId) !== null)
				{
					$request->setParameter('errorNumber', 0);
				}
				else
				{
					$request->setParameter('errorNumber', 1);
					$request->setParameter('customMsg', 'Upload error');
				}
				return change_View::SUCCESS;
		}
		return change_View::NONE;
	}
	
	private function getDataProvider($resourceType)
	{
		if(Framework::hasConfiguration('modules/website/richtextConnector/' . ucfirst($resourceType) . 'ChildrenFinder'))
		{
			$className = Framework::getConfiguration('modules/website/richtextConnector/' . ucfirst($resourceType) . 'ChildrenFinder');
		}
		else
		{
			$className = 'website_' . ucfirst($resourceType) . 'ChildrenFinder';
		}
		if (f_util_ClassUtils::classExists($className))
		{
			$finder = new $className();
			return $finder;
		}
		return null;
	}
	
	public function isSecure()
	{
		return false;
	}
	
	private function convertRessourceTypeToModuleName($resourceType)
	{
		return $resourceType == 'Page' ? 'website' : 'media';
	}
	
	private function resolveCurrentFolder($moduleName, $currentFolder)
	{
		$parentIds = array();		
		$parentIds[] = ModuleService::getInstance()->getInstance()->getRootFolderId($moduleName);
		
		if ($currentFolder != '/')
		{
			foreach (explode('/', $currentFolder) as $folderId)
			{
				$id = intval($folderId);
				if (intval($id) <= 0 || in_array($id, $parentIds))
				{
					continue;
				}
				$parentIds[] = $id;
			}
		}
		return $parentIds;
	}
	
	private function getNodeList($resourceType, $parentId, $folderOnly = false)
	{
		$finder = $this->getDataProvider($resourceType);
		if ($finder !== null)
		{
			$finder->setNavigationOnly($folderOnly);
			$parentDocument = DocumentHelper::getDocumentInstance($parentId);
			return $finder->getChildren($parentDocument);
		}
		return new website_tree_NodeList();
	}

	private function createFolder($parentId, $name)
	{
		$finder = $this->getDataProvider('file');
		if ($finder !== null)
		{
			return $finder->createFolder($parentId, $name);			
		}
		return null;
	}
	
	private function fileUpload($parentId)
	{
		if (isset($_FILES['NewFile']) && ! is_null($_FILES['NewFile']['tmp_name']))
		{
			$oFile = $_FILES['NewFile'];
			$fileName = $oFile['name'];
			$serverFilePath = $oFile['tmp_name'];
			$fileAlt = $_POST['NewFileAlt'];
			return $this->getDataProvider('file')->fileUpload($fileName, $serverFilePath, $parentId, $fileAlt);
		}
		return null;
	}
}