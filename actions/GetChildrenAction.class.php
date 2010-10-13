<?php
class website_GetChildrenAction extends f_action_BaseJSONAction
{
	
	/**
	 * @see f_action_BaseAction::_execute()
	 *
	 * @param Context $context
	 * @param Request $request
	 */
	protected function _execute($context, $request)
	{
		$result = array();
		$parentId = $this->getDocumentIdFromRequest($request);
		if (f_util_StringUtils::isEmpty($parentId))
		{
			$result[] = DocumentHelper::getDocumentInstance(ModuleService::getInstance()->getRootFolderId($this->getModuleName(null)));
		}
		else
		{	
			$parent = DocumentHelper::getDocumentInstance($parentId);
			$children = $this->getPersistentProvider()->createQuery('modules_generic/Document')
				->add(Restrictions::childOf($parent->getId()))
				->add(Restrictions::in('model', $this->getChildrenModelArray($parent)))	
				->find();
				
			foreach ($children as $child)
			{
				$result[] = $this->getDocumentInfos($child);
			}
		}
		return $this->sendJSON($result);
	}
	
	/**
	 * @param f_persistentdocument_PersistentDocumentImpl $parent
	 * @return array<string>
	 */
	private function getChildrenModelArray($parent)
	{
		if ($parent instanceof generic_persistentdocument_rootfolder) 
		{
			return array("modules_website/website");
		}
		else if ($parent instanceof website_persistentdocument_website )
		{
			return array("modules_website/topic", "modules_website/menufolder");
		}
		else if ($parent instanceof website_persistentdocument_menufolder)
		{
			return array("modules_website/menu");		
		}
	}
	
	/**
	 * @param f_persistentdocument_PersistentDocumentImpl $document
	 * @return array
	 */
	protected function getDocumentInfos($document)
	{
		$isContextLangAvailable = $document->isContextLangAvailable();
		$label = $isContextLangAvailable ? $document->getI18nInfo()->getLabel() : $document->getI18nInfo()->getVoLabel();				
		return array('id' => $document->getId(), 'model' => $document->getDocumentModelName(), 
			'vo' => $document->getLang(), 'rev' => $document->getDocumentversion(),
			'localized' => $isContextLangAvailable, 'label' => $label); 
	}
		
	/**
	 * @return boolean
	 */
	public function isSecure()
	{
		return false;
	}
}