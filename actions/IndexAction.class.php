<?php
class website_IndexAction extends change_Action
{
	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	public function _execute($context, $request)
	{
		try 
		{
			$host = $_SERVER['HTTP_HOST'];
			website_UrlRewritingService::getInstance()->initCurrrentWebsite($host);
			$ws = website_WebsiteService::getInstance();
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
				$request->setParameter('pageref', $homePage->getId());
				$context->getController()->forward('website', 'Display');
			}
		} 
		catch (Exception $e) 
		{
			Framework::exception($e);
			require(f_util_FileUtils::buildWebeditPath("site-disabled.php"));
		}		
		return change_View::NONE ;
	}

	/**
	 * @return string
	 */
	public function getRequestMethods()
	{
		return change_Request::GET | change_Request::POST;
	}

	/**
	 * @return boolean
	 */
	public function isSecure()
	{
		return false;
	}
}
