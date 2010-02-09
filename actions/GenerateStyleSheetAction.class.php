<?php
/**
 * website_GenerateStyleSheetAction
 * @package modules.website.actions
 */
class website_GenerateStyleSheetAction extends website_Action
{
	
	/**
	 * @see f_action_BaseAction::isSecure()
	 *
	 * @return boolean
	 */
	public function isSecure()
	{
		return false;
	}

	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		$this->setContentType('text/css; charset=UTF-8');
		if (Framework::inDevelopmentMode())
		{
			controller_ChangeController::setNoCache();
		}
		$prs = website_PageRessourceService::getInstance();
		$parameters = explode("/", $request->getParameter("param"));
		if (count($parameters) != 6)
		{
			return View::NONE;
		}
		
		$protocol = $parameters[0];
		$websiteId = $parameters[1];
		RequestContext::getInstance()->setLang($parameters[2]);
		$website = DocumentHelper::getDocumentInstance($websiteId);
		website_WebsiteModuleService::getInstance()->setCurrentWebsite($website);
		
		$engine = $parameters[3];
		$version = $parameters[4];
		$stylesheet = substr($parameters[5], 0 , strrpos($parameters[5], '.'));
		$skinSepIndex = strpos($stylesheet, '-');
		
		if ($skinSepIndex !== false)
		{
			$stylesheetBaseName = substr($stylesheet, 0,  $skinSepIndex);
			$skinId = substr($stylesheet, $skinSepIndex+1);			
			$prs->setSkin(DocumentHelper::getDocumentInstance(intval($skinId)));
		}
		else
		{
			$stylesheetBaseName = $stylesheet;
		}
		
		if ($stylesheetBaseName == website_PageRessourceService::GLOBAL_SCREEN_NAME)
		{
			echo $prs->getGlobalScreenStylesheet($engine, $version, $protocol);
		}
		else if ($stylesheetBaseName == website_PageRessourceService::GLOBAL_PRINT_NAME)
		{
			echo $prs->getGlobalPrintStylesheet($engine, $version, $protocol);
		}
		else if ($stylesheetBaseName == website_PageRessourceService::GLOBAL_DASHBOARD_NAME)
		{
			echo $prs->getDashboardStylesheet($engine, $version, $protocol);
		}
		else 
		{
			echo $prs->getStylesheet($stylesheetBaseName, $engine, $version, $protocol);
		}
		return View::NONE;		
	}
}