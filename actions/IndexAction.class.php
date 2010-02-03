<?php
class website_IndexAction extends website_Action
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		$ws = website_WebsiteModuleService::getInstance();
		$website = $ws->getCurrentWebsite(!RequestContext::getInstance()->isLangDefined());

		if ($website instanceof website_persistentdocument_website)
		{
			if (($homePage = $ws->getIndexPage($website)) === null || !$homePage->isPublished())
			{
				require(f_util_FileUtils::buildWebappPath("www/site-disabled.php"));
				return View::NONE ;
			}
			else
			{
				$request->setParameter(K::PAGE_REF_ACCESSOR, $homePage->getId());
				$fwdModule = 'website';
				$fwdAction = 'Display';
			}
		}
		else
		{
			$fwdModule = AG_ERROR_404_MODULE;
			$fwdAction = AG_ERROR_404_ACTION;
		}

		$context->getController()->forward($fwdModule, $fwdAction);

		return View::NONE ;
	}


	/* Frontoffice homepage: only responds to GET requests! */
	public function getRequestMethods()
	{
		return Request::GET | Request::POST;
	}


	/* Frontoffice homepage: not secure! */
	public function isSecure()
	{
		return false;
	}
}
