<?php
/**
 * @package modules.website.actions
 */
class website_GetRobotsTxtAction extends change_Action
{
	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	public function _execute($context, $request)
	{
		$wsms = website_WebsiteService::getInstance();
		$websiteInfo  = $wsms->getWebsiteInfos($_SERVER['HTTP_HOST']);
		$contents = '';
		$website = null;
		
		if ($websiteInfo !== null)
		{
			$lang = $websiteInfo['langs'][0];
			RequestContext::getInstance()->setLang($lang);
			$wsms->setCurrentWebsiteId($websiteInfo['id']);
			
			$website = $wsms->getCurrentWebsite();
			$contents = $website->getRobottxt();
		}
		
		if (f_util_StringUtils::isEmpty($contents))
		{
			$path = f_util_FileUtils::buildWebeditPath('media', 'frontoffice', $_SERVER['HTTP_HOST'] . '.robots.txt');
			if (file_exists($path))
			{
				$contents = f_util_FileUtils::read($path);
			}
			else
			{
				$contents = f_util_FileUtils::read(f_util_FileUtils::buildWebeditPath('media', 'frontoffice', 'robots.txt'));
			}
		}
	
		if ($website !== null && ModuleService::getInstance()->moduleExists('seo'))
		{
			$contents = $this->appendSeoContent($contents, $website);
		}
		
		header('Content-type: text/plain');
		header('Content-length: '.strlen($contents));
		echo $contents;
		return change_View::NONE;	
	}
	
	/**
	 * @param string $contents
	 * @param website_persistentdocument_website $website
	 * @return string
	 */
	protected function appendSeoContent($contents, $website)
	{
		return seo_ModuleService::getInstance()->appendRobotTxtContent($contents, $website);
	}

	/**
	 * @return Boolean
	 */
	public function isSecure()
	{
		return false;
	}
}