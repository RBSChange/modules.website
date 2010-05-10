<?php
/**
 * @package modules.website.actions
 */
class website_GetFaviconAction extends f_action_BaseAction
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
			$favicon = $website->getFavicon();
			if ($favicon !== null)
			{
				$request->setParameter('cmpref', $favicon->getId());
				$context->getController()->forward('media', 'Display');	
				return View::NONE;
			}
		}	
		$this->setContentType($website->getFaviconMimeType());
		$path = f_util_FileUtils::buildWebeditPath('media', 'frontoffice', $_SERVER['HTTP_HOST'] . '.ico');
		if (file_exists($path))
		{
			readfile($path);	
		}
		else
		{
			readfile(f_util_FileUtils::buildWebeditPath('media', 'frontoffice', 'favicon.ico'));
		}	
		return View::NONE;			
	}
	
	public function isSecure()
	{
		return false;
	}
}