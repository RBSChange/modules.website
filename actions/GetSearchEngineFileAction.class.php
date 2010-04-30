<?php
/**
 * @package modules.website.actions
 */
class website_GetSearchEngineFileAction extends f_action_BaseAction
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{	
		if (Framework::isDebugEnabled())
		{
			Framework::debug(__METHOD__);
			Framework::debug(var_export($request->getParameters(), true));
		}
		
		$wsms = website_WebsiteModuleService::getInstance();
		$websiteInfo  = $wsms->getWebsiteInfos($_SERVER['HTTP_HOST']);
		if ($websiteInfo !== null)
		{
			$lang = $websiteInfo['langs'][0];
			RequestContext::getInstance()->setLang($lang);
			$wsms->setCurrentWebsiteId($websiteInfo['id']);
			
			$website = $wsms->getCurrentWebsite();
			$engine = $request->getParameter('engine');
			$id = $request->getParameter('id');
			if ($this->sendContent($website, $engine, $id))
			{
				return View::NONE;
			}
		}
		
		f_web_http_Header::setStatus(404);
    	return View::NONE;
	}
	
	/**
	 * @param website_persistentdocument_website $website
	 * @param string $engine
	 * @param string $id
	 * @return boolean
	 */
	private function sendContent($website, $engine, $id)
	{
		switch ($engine)
		{
			case 'msn':
				if (f_util_StringUtils::isNotEmpty($website->getMsnfilecontent()))
				{
					header('Content-type: text/xml');
					echo $website->getMsnfilecontent();
					return true;
				}
				break;
			case 'google':
				if ($id == $website->getGooglefileid() && f_util_StringUtils::isNotEmpty($website->getGooglefilecontent()))
				{
					header('Content-type: text/html');
					echo $website->getGooglefilecontent();
					return true;
				}
				break;
			case 'yahoo':
				if ($id == $website->getYahoofileid() && f_util_StringUtils::isNotEmpty($website->getYahoofilecontent()))
				{
					header('Content-type: text/html');
					echo $website->getYahoofilecontent();
					return true;
				}
				break;
		}
		return false;
	}

	/**
	 * @return Boolean
	 */
	public function isSecure()
	{
		return false;
	}
}