<?php
/**
 * @date Wed Jan 31 17:52:45 CET 2007
 * @author INTbonjF
 */
class website_AddToMenuAction extends website_Action
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		$pagesId = $this->getDocumentIdArrayFromRequest($request);
		$menuId  = $request->getParameter(K::DESTINATION_ID_ACCESSOR);
		
		$menuObject = DocumentHelper::getDocumentInstance($menuId); 
		foreach ($pagesId as $pageId)
		{
			$document = DocumentHelper::getDocumentInstance($pageId);		
			$menuItemDoc = website_MenuitemdocumentService::getInstance()->getNewDocumentInstance();
			$menuItemDoc->setLabel($document->getLabel());
			$menuItemDoc->setDocument($document);
			$menuItemDoc->save($menuObject->getId());
		}

		return self::getSuccessView();
	}
}