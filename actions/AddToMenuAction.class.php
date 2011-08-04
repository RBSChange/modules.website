<?php
class website_AddToMenuAction extends change_JSONAction
{
	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	public function _execute($context, $request)
	{
		$pagesId = $this->getDocumentIdArrayFromRequest($request);
		$menuId  = $request->getParameter(K::DESTINATION_ID_ACCESSOR);
		$label = array("");
		$menuObject = DocumentHelper::getDocumentInstance($menuId); 
		$menulabel = $menuObject->getLabel();
		foreach ($pagesId as $pageId)
		{
			$document = DocumentHelper::getDocumentInstance($pageId);		
			$menuItemDoc = website_MenuitemdocumentService::getInstance()->getNewDocumentInstance();
			$label[] = $document->getLabel();
			$menuItemDoc->setLabel($document->getLabel());
			$menuItemDoc->setDocument($document);
			$menuItemDoc->save($menuObject->getId());
		}

		return $this->sendJSON(array('message' => LocaleService::getInstance()->transBO('m.website.bo.actions.addtomenuactionsuccess', 
			array(), 
			array('label' => implode("\n  ", $label), 'menulabel' => $menulabel))));
	}
}