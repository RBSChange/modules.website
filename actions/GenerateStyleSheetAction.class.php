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
		$nbParameters = count($parameters); 

		try 
		{
			ob_start();
			if ($nbParameters != 6 && $nbParameters != 7)
			{
				throw new Exception('Invalid number of parameter: ' . $nbParameters);
			}
			
			$protocol = $parameters[0];
			$website = DocumentHelper::getDocumentInstance($parameters[1], "modules_website/website");
			
			RequestContext::getInstance()->setLang($parameters[2]);		
			website_WebsiteModuleService::getInstance()->setCurrentWebsite($website);
			
			$engine = $parameters[3];
			$version = $parameters[4];
			if ($nbParameters == 7)
			{
				$template = DocumentHelper::getDocumentInstance($parameters[5], "modules_theme/pagetemplate");
				$stylesheet = substr($parameters[6], 0 , strrpos($parameters[6], '.'));
				$skinSepIndex = strpos($stylesheet, '-');
			}
			else
			{
				$template = null;
				$stylesheet = substr($parameters[5], 0 , strrpos($parameters[5], '.'));
				$skinSepIndex = strpos($stylesheet, '-');
			}
				
			if ($skinSepIndex !== false)
			{
				$stylesheetBaseName = substr($stylesheet, 0,  $skinSepIndex);
				$skinId = substr($stylesheet, $skinSepIndex+1);			
				$prs->setSkin(DocumentHelper::getDocumentInstance(intval($skinId), "modules_skin/skin"));
			}
			else
			{
				$stylesheetBaseName = $stylesheet;
			}
			
			if (!$template)
			{
				echo $prs->getStylesheet($stylesheetBaseName, $engine, $version, $protocol);
			}
			else if ($stylesheetBaseName == website_PageRessourceService::GLOBAL_SCREEN_NAME)
			{
				echo $prs->getTemplateScreenStylesheet($template, $engine, $version, $protocol);
			}
			else if ($stylesheetBaseName == website_PageRessourceService::GLOBAL_PRINT_NAME)
			{
				echo $prs->getTemplatePrintStylesheet($template, $engine, $version, $protocol);
			}
			else
			{
				throw new Exception('Invalid styleSheet name:' . $stylesheetBaseName);
			}
			ob_end_flush();			
		}
		catch (Exception $e)
		{
			Framework::exception($e);
			ob_end_clean();	
			f_web_http_Header::setStatus(404);
			echo $e->getMessage();
		}
		return View::NONE;		
	}
}