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
		$this->renderStylesheets($this->getDocumentInstanceFromRequest($request));
		$rq->endI18nWork();		
		return View::NONE;
	}
	
	/**
	 * @param website_persistentdocument_page $page
	 */
	private function renderStylesheets($page)
	{
		// include stylesheets
		$moduleService = ModuleService::getInstance();
		$availableModules = $moduleService->getModules();	
		$styleArray = array('modules.website.backoffice', 'modules.uixul.backoffice', 
		 'modules.generic.backoffice', 'modules.uixul.EditContent');
			
		foreach ($availableModules as $availableModule)
		{
			$moduleName = $moduleService->getShortModuleName($availableModule);
			$styleArray[] = 'modules.' . $moduleName . '.bindings';
		}

		$ss = StyleService::getInstance();

		$skinId = $page->getSkinId();
		$skin = ($skinId) ? DocumentHelper::getDocumentInstance($skinId) : null;		
		foreach ($styleArray as $stylename)
		{
			echo $ss->getCSS($stylename, $ss->getFullEngineName('xul'), $skin);
		}	
	}
}