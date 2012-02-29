<?php
/**
 * website_GenerateStyleSheetAction
 * @package modules.website.actions
 */
class website_GenerateStyleSheetAction extends f_action_BaseAction
{
	/**
	 * @see f_action_BaseAction::isSecure()
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
		if (Framework::isInfoEnabled())
		{
			Framework::info(__METHOD__ . ' ' . $request->getParameter("param"));
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
			$websiteId = intval($parameters[1]);
			if ($websiteId <= 0)
			{
				$website =  website_WebsiteModuleService::getInstance()->getDefaultWebsite();
			}
			else
			{
				$website = DocumentHelper::getDocumentInstance($websiteId, "modules_website/website");
			}
			
			RequestContext::getInstance()->setLang($parameters[2]);		
			website_WebsiteModuleService::getInstance()->setCurrentWebsite($website);
			
			$engine = $parameters[3];
			$version = $parameters[4];
			if ($nbParameters == 7)
			{
				$template = theme_persistentdocument_pagetemplate::getInstanceById($parameters[5]);
				$stylesheet = substr($parameters[6], 0 , strrpos($parameters[6], '.'));
			}
			else
			{
				$template = null;
				$stylesheet = substr($parameters[5], 0 , strrpos($parameters[5], '.'));
			}
			
			$matches = null;
			if (preg_match('/^(.*)-([0-9]*)$/', $stylesheet, $matches))
			{
				$stylesheetBaseName = $matches[1];
				$skinId = $matches[2];			
				$prs->setSkin(DocumentHelper::getDocumentInstance(intval($skinId), "modules_skin/skin"));
			}
			else
			{
				$stylesheetBaseName = $stylesheet;
			}
			
			if (!$template)
			{
				if (strpos($stylesheetBaseName, ','))
				{
					$fullEngine = $engine .'.' .$version;
					$names = explode(',', $stylesheetBaseName);
					$mediaType = array_pop($names);
					foreach ($names as $stylesheetName) 
					{
						if (Framework::inDevelopmentMode())
						{
							echo "/* $stylesheetName $mediaType $fullEngine */\n";
						}
						echo StyleService::getInstance()->getCSS($stylesheetName, $fullEngine);
					}
					$cssContent = ob_get_contents();		
					$cssFilePath = f_util_FileUtils::buildWebCachePath('css', $request->getParameter("param"));
					Framework::info(__METHOD__ . ' ' . $cssFilePath);
					f_util_FileUtils::writeAndCreateContainer($cssFilePath, $cssContent, f_util_FileUtils::OVERRIDE);
					if (file_exists($cssFilePath . '.deleted'))
					{
						@unlink($cssFilePath . '.deleted');
					}						
				}
				else
				{
					echo $prs->getStylesheet($stylesheetBaseName, $engine, $version, $protocol);
				}
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
