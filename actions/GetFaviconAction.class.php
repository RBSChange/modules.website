<?php
/**
 * @package modules.website.actions
 */
class website_GetFaviconAction extends change_Action
{
	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	public function _execute($context, $request)
	{
		if (Framework::isDebugEnabled())
		{
			Framework::debug(__METHOD__);
			Framework::debug(var_export($request->getParameters(), true));
		}
		$wsms = website_WebsiteService::getInstance();
		$websiteInfo  = $wsms->getWebsiteInfos($_SERVER['HTTP_HOST']);
		$website = null;
		
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
				return change_View::NONE;
			}
		}	
		$mimeType = ($website) ? $website->getFaviconMimeType() : 'image/x-icon';
		
		$this->setContentType($mimeType);
		$path = f_util_FileUtils::buildProjectPath('media', 'frontoffice', $_SERVER['HTTP_HOST'] . '.ico');
		if (file_exists($path))
		{
			readfile($path);	
		}
		else
		{
			readfile(f_util_FileUtils::buildProjectPath('media', 'frontoffice', 'favicon.ico'));
		}	
		return change_View::NONE;			
	}
	
	public function isSecure()
	{
		return false;
	}
}