<?php
/**
 * @date Thu Feb 01 21:15:12 CET 2007
 * @author INTbonjF
 */
class website_RewriteUrlAction extends f_action_BaseAction
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
    {
    	$requestedUrl = $request->getParameter(K::URL_REWRITE_PAGE_NAME_ACCESSOR);

    	// TODO: enhance. Remove parameters from get for future URL (re)generation "from scratch"
    	$request->removeParameter(K::URL_REWRITE_PAGE_NAME_ACCESSOR);
    	$request->removeParameter('module');
    	$request->removeParameter('action');
    	
    	unset($_GET[K::URL_REWRITE_PAGE_NAME_ACCESSOR]);
    	unset($_GET["module"]);
    	unset($_GET["action"]);
   
		if ($requestedUrl{0} !== '/')
		{
			$requestedUrl = '/' . $requestedUrl;
		}		
		$wsms = website_WebsiteModuleService::getInstance();
		$websiteInfo  = $wsms->getWebsiteInfos($_SERVER['HTTP_HOST']);
		if ($websiteInfo === null)
		{
			Framework::error("WEBSITE not found for host : " . $_SERVER['HTTP_HOST']);
			return View::NONE;
		}
		
		$rc = RequestContext::getInstance();
		$lang = null;
		
		if ($websiteInfo['localizebypath'])
		{
			$pattern = '/^\/('. implode('|', $websiteInfo['langs']) .')\//';
			$matches = array();
		 	if ($requestedUrl === '/'.$websiteInfo['langs'][0].'/')
			{
				 $context->getController()->redirectToUrl('/');
			}
			elseif (preg_match($pattern, $requestedUrl, $matches))
			{
				$lang = $matches[1];
			    $requestedUrl = substr($requestedUrl, 3);
			}
			elseif (preg_match('/^\/('. implode('|', $websiteInfo['langs']) .')$/', $requestedUrl, $matches))
			{
				$lang = $matches[1];
				if ($lang === $websiteInfo['langs'][0])
				{
					$context->getController()->redirectToUrl('/');
				}
				else
				{
					$context->getController()->redirectToUrl($requestedUrl.'/');
				}
				return View::NONE;
			}
		}
		
		if ($lang === null) { $lang = $websiteInfo['langs'][0]; }
		$rc->setLang($lang);		
		$wsms->setCurrentWebsiteId($websiteInfo['id']);
		
		if ('/' == $requestedUrl)
		{
		    $context->getController()->forward(AG_DEFAULT_MODULE, AG_DEFAULT_ACTION);
    	    return View::NONE;
		}
	
		$urlService = website_UrlRewritingService::getInstance();
		$rule = $urlService->getRuleByUrl($requestedUrl);
		if ($rule instanceof website_lib_urlrewriting_Rule)
		{
			$urlService->redirect($rule, $context->getController());
			return View::NONE;
		}

		$request->setAttribute('requestedUrl', $requestedUrl);
		if (array_key_exists('HTTP_REFERER', $_SERVER))
		{
			$request->setAttribute('lastVisitedUrl', $_SERVER['HTTP_REFERER']);
		}
		else
		{
			$request->setAttribute('lastVisitedUrl', '');
		}
		
		$context->getController()->forward(AG_ERROR_404_MODULE, AG_ERROR_404_ACTION);
    	return View::NONE;
    }


    public function getRequestMethods()
    {
    	return Request::GET | Request::POST;
    }


    /**
     * @return boolean
     */
    public function isSecure()
    {
        return false;
    }
}
