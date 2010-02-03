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
		$modules = array();
		$moduleService = ModuleService::getInstance();
		$availableModules = $moduleService->getModules();		
		foreach ($availableModules as $availableModule)
		{
			$moduleName = $moduleService->getShortModuleName($availableModule);
			$modules[] = $moduleName;
		}
		
		$styleArray = array('modules.generic.frontoffice', 'modules.generic.richtext',
			'modules.website.frontoffice', 'modules.website.richtext');

		// Module backoffice styles :
		foreach ($modules as $module)
		{
			
			if (($module == 'website') || ($module == 'uixul') || ($module == 'generic'))
			{
				$styleArray[] = 'modules.' . $module . '.backoffice';
			}
			else
			{
				$styleArray[] = 'modules.' . $module . '.frontoffice';
			}
			$styleArray[] = 'modules.' . $module . '.bindings';
		}
		
		$styleArray[] = 'modules.uixul.EditContent';
		$ss = StyleService::getInstance();
		
		$skinId = $page->getSkinId();
		$skin = ($skinId) ? DocumentHelper::getDocumentInstance($skinId) : null;
		
		foreach ($styleArray as $stylename)
		{
			echo $ss->getCSS($stylename, $ss->getFullEngineName('xul'), $skin);
		}	
	}
}