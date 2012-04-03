<?php
class website_IndexAction extends f_action_BaseAction
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		try 
		{
			$host = $_SERVER['HTTP_HOST'];
			website_UrlRewritingService::getInstance()->initCurrrentWebsite($host);
			$ws = website_WebsiteModuleService::getInstance();
			$website = $ws->getCurrentWebsite();
			if ($website->getLocalizebypath())
			{
				if (Framework::isInfoEnabled())
				{
					Framework::info(__METHOD__ . ' redirect on VO homepage');
				}
				$request->setParameter('location', LinkHelper::getDocumentUrl($website, $website->getLang()));
				$context->getController()->forward('website', 'Redirect');
			}
			else
			{		
				$homePage = $ws->getIndexPage($website, false);
				if ($homePage  === null || !$homePage->isPublished())
				{
					throw new Exception('Website has no published home page');
				}
				$request->setParameter(K::PAGE_REF_ACCESSOR, $homePage->getId());
				$context->getController()->forward('website', 'Display');
			}
		} 
		catch (Exception $e) 
		{
			Framework::exception($e);
			require(f_util_FileUtils::buildWebeditPath("site-disabled.php"));
		}		
		return View::NONE ;
	}

	/**
	 * @return string
	 */
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
