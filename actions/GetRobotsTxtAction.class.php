<?php
/**
 * @package modules.website.actions
 */
class website_GetRobotsTxtAction extends f_action_BaseAction
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
			$contents = $website->getRobottxt();
			if (f_util_StringUtils::isNotEmpty($contents))
			{
				header('Content-type: text/plain');
				header('Content-length: '.strlen($contents));
				echo $contents;
				return View::NONE;
			}
		}
		
		$path = f_util_FileUtils::buildWebappPath('media', 'frontoffice', $_SERVER['HTTP_HOST'] . '.robots.txt');
		if (file_exists($path))
		{
			$contents = f_util_FileUtils::read($path);
		}
		else
		{
			$contents = f_util_FileUtils::read(f_util_FileUtils::buildWebappPath('media', 'frontoffice', 'robots.txt'));
		}
		header('Content-type: text/plain');
		header('Content-length: '.strlen($contents));
		echo $contents;
		return View::NONE;	
	}

	/**
	 * @return Boolean
	 */
	public function isSecure()
	{
		return false;
	}
}