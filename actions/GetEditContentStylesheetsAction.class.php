<?php
class website_GetEditContentStylesheetsAction extends f_action_BaseAction
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		header("Expires: " . gmdate("D, d M Y H:i:s", time()+28800) . " GMT");
		header('Content-type: text/css');
	    $rq = RequestContext::getInstance();
        $rq->beginI18nWork($rq->getUILang());
        $pageId = $this->getDocumentIdFromRequest($request);
        if (intval($pageId) > 0)
        {
        	controller_ChangeController::getInstance()->setNoCache();
			$this->renderStylesheets(DocumentHelper::getDocumentInstance($pageId));
        }
        else
        {
        	$this->renderBindings();
        }
		$rq->endI18nWork();		
		return View::NONE;
	}
	
	private function renderBindings()
	{
		$styleArray = array('modules.website.backoffice', 'modules.uixul.backoffice', 
		 'modules.generic.backoffice', 'modules.uixul.EditContent', 'modules.uixul.bindings');
		
		$ss = StyleService::getInstance();
		foreach ($styleArray as $stylename)
		{
			echo $ss->getCSS($stylename, $ss->getFullEngineName('xul'));
		}
	}
	
	/**
	 * @param website_persistentdocument_page $page
	 */
	private function renderStylesheets($page)
	{
		$ss = StyleService::getInstance();
		$skinId = $page->getSkinId();
		$skin = ($skinId) ? DocumentHelper::getDocumentInstance($skinId) : null;		
		
		$wprs = website_PageRessourceService::getInstance();
		$template = $wprs->getPageTemplate($page);	
		foreach ($template->getScreenStyleIds() as $styleId) 
		{
			echo $ss->getCSS($styleId, $ss->getFullEngineName('xul'), $skin);
		}
		
		$containerStyleId = $wprs->getContainerStyleIdByAncestors($page->getDocumentService()->getAncestorsOf($page));
		if ($containerStyleId)
		{
			echo $ss->getCSS($containerStyleId, $ss->getFullEngineName('xul'), $skin);
		}
	}
}