<?php
class website_InsertJSONAction extends generic_InsertJSONAction
{

	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	public function _execute($context, $request)
	{
		$modelName = $request->getParameter('modelname');
		if ($modelName === 'modules_website/pageversion')
		{	
			return $this->insertPageVersion($context, $request);
		}
		
		$propertiesNames = explode(',', $request->getParameter('documentproperties', ''));
		$propertiesValue = array();
		foreach ($propertiesNames as $propertyName)
		{
			if ($request->hasParameter($propertyName))
			{
				$propertiesValue[$propertyName] = $request->getParameter($propertyName);
			}			
		}		
	
		$model = f_persistentdocument_PersistentDocumentModel::getInstanceFromDocumentModelName($modelName);
		$documentService = $model->getDocumentService();
		$document = $documentService->getNewDocumentInstance();

		uixul_DocumentEditorService::getInstance()->importFieldsData($document, $propertiesValue);

		$parentNodeId = intval($request->getParameter('parentref'));
		if ($parentNodeId <= 0) { $parentNodeId = null; }

		$documentService->save($document, $parentNodeId);
		$this->logAction($document);
		
		if ($document instanceof website_persistentdocument_page && $parentNodeId) 
		{
			$this->setIndexPage($document, $parentNodeId);
		}

		return $this->sendJSON(array('id' => $document->getId(), 'lang' => $document->getLang(), 'label' => $document->getLabel()));
	}
	
	/**
	 * @param website_persistentdocument_page $document
	 * @param integer $parentNodeId
	 */
	private function setIndexPage($document, $parentNodeId)
	{
		$parentDocument = DocumentHelper::getDocumentInstance($parentNodeId);

		if ($parentDocument instanceof website_persistentdocument_topic)
		{
			$document->setTopic($parentDocument);
			$indexPage = $parentDocument->getIndexPage();
			if (is_null($indexPage))
			{
				website_PageService::getInstance()->makeIndexPage($document, false);
				$actionName = strtolower('settopicindexpage.' . $document->getPersistentModel()->getDocumentName());
				UserActionLoggerService::getInstance()->addCurrentUserDocumentEntry($actionName, $document, array(), 'website');
			}
		} 
		else if ($parentDocument instanceof website_persistentdocument_website)
		{
			$indexPage = $parentDocument->getIndexPage();
			if (is_null($indexPage))
			{
				website_PageService::getInstance()->makeHomePage($document);
				$actionName = strtolower('sethomepage.' . $document->getPersistentModel()->getDocumentName());
				UserActionLoggerService::getInstance()->addCurrentUserDocumentEntry($actionName, $document, array(), 'website');
			}
		}
	}
	
	/**
	 * @param f_persistentdocument_PersistentDocument $document
	 */
	protected function logAction($document, $info = array())
	{
		$this->logged = true;
		$moduleName = $this->getModuleName();
		$actionName = strtolower($this->getActionName());
		if ($document instanceof f_persistentdocument_PersistentDocument)
		{
			$actionName .= '.' . strtolower($document->getPersistentModel()->getDocumentName());
		}
		if (Framework::isDebugEnabled())
		{
			Framework::debug(__METHOD__."($moduleName, $actionName)");
		}		
		UserActionLoggerService::getInstance()->addCurrentUserDocumentEntry($actionName, $document, $info, $moduleName);
	}
		
	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 */	
	private function insertPageVersion($context, $request)
	{
		$parent = $this->getDocumentInstanceFromRequest($request);
		if (!$parent instanceof website_persistentdocument_page ) 
		{
			throw new Exception('Not valid type (page) for parent node: ' + get_class($parent));
		}	
			
		//For correction get master document
		if ($parent->getCorrectionofid())
		{
			$parent = DocumentHelper::getDocumentInstance($parent->getCorrectionofid());
		}
		
		$propertiesNames = explode(',', $request->getParameter('documentproperties', ''));
		$pageversionService = website_PageversionService::getInstance();
		$document = website_PageversionService::getInstance()->getNewDocumentInstance();
			
		if ($request->hasParameter('duplicate'))
		{
			$propertiesValue = uixul_DocumentEditorService::getInstance()->exportFieldsData($parent, $propertiesNames);	
			$pageversionService->duplicatePageContent($parent, $document);
		}
		else
		{
			$propertiesValue = array();
		}
		
		foreach ($propertiesNames as $propertyName)
		{
			if ($request->hasParameter($propertyName))
			{
				$propertiesValue[$propertyName] = $request->getParameter($propertyName);
			}
		}	
		
		uixul_DocumentEditorService::getInstance()->importFieldsData($document, $propertiesValue);
		
		$pageversionService->addNewVersion($document, $parent->getId());
		
		$this->logAction($document);

		return $this->sendJSON(array('id' => $document->getId(), 
		'lang' => $document->getLang(),
		'label' => $document->getLabel()));		
	}
}