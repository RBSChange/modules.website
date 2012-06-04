<?php
/**
 * website_GenerateJavascriptAction
 * @package modules.website.actions
 */
class website_GenerateJavascriptAction extends change_Action
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
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	public function _execute($context, $request)
	{
		$this->setContentType('application/javascript');
		if (Framework::inDevelopmentMode())
		{
			change_Controller::setNoCache();
		}
		else
		{
			header('Pragma:');
			header('Expires:');
			header('Cache-Control:');
		}
		if (Framework::isInfoEnabled())
		{
			Framework::info(__METHOD__ . ' ' . $request->getParameter("param"));
		}
		$parameters = explode("/", $request->getParameter("param"));
		$nbParameters = count($parameters);
		try 
		{
			ob_start();
			if ($nbParameters < 5)
			{
				Framework::info(__METHOD__ . var_export($parameters, true));
				throw new Exception('Invalid number of parameter: ' . $nbParameters);
			}
			$protocol = $parameters[0];
			
			$websiteId = intval($parameters[1]);
			if ($websiteId <= 0)
			{
				$website =  website_WebsiteService::getInstance()->getDefaultWebsite();
			}
			else
			{
				$website = DocumentHelper::getDocumentInstance($websiteId, "modules_website/website");
			}
			
			RequestContext::getInstance()->setLang($parameters[2]);		
			website_WebsiteService::getInstance()->setCurrentWebsite($website);
			
			if (intval($parameters[3]) > 0)
			{
				$template = theme_persistentdocument_pagetemplate::getInstanceById(intval($parameters[3]));
				$frontofficeScripts = $template->getScriptIds();
			}
			else
			{
				$frontofficeScripts = array();
			}

			$js = website_JsService::getInstance();
			foreach ($frontofficeScripts as $script)
			{
				$js->registerScript($script);
			}
			
			$scriptRegistryOrdered = array_keys($js->getComputedRegisteredScripts());
			
			$names = array_slice($parameters, 4);
			if (count($names) === 1 &&  $names[0] === 'template')
			{
				echo "// **** Context vars ****\n";
				echo $js->getJS('init');
				
				foreach ($scriptRegistryOrdered as $script)
				{
					echo "\n// **** $script ****\n";					
					echo $js->getJS($script);
				}
			}
			else
			{
				$page = array_pop($names);
				if ($page === 'block')
				{
					$extraJSNames = $names;
				}
				else
				{
					foreach ($names as $script)
					{
						$js->registerScript($script);
					}
					$extraJSNames = array_keys($js->getComputedRegisteredScripts());
				}
				foreach ($extraJSNames as $script) 
				{
					if (!in_array($script, $scriptRegistryOrdered))
					{
						echo "\n// **** $script ****\n";					
						echo $js->getJS($script);						
					}
				}
			}
			$content = ob_get_contents();		
			$jsFileScript = f_util_FileUtils::buildWebCachePath('js', implode(DIRECTORY_SEPARATOR, $parameters)) . '.js';
			f_util_FileUtils::writeAndCreateContainer($jsFileScript, $content, f_util_FileUtils::OVERRIDE);
			if (file_exists($jsFileScript . '.deleted'))
			{
				@unlink($jsFileScript . '.deleted');
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
		return change_View::NONE;
	}
}