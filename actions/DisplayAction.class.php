<?php
class website_DisplayAction extends website_Action
{
	protected function getDocumentIdArrayFromRequest($request)
	{
		$pageIds = array();
		// Page ID could come in (too) many flavours :
		if ($request->hasParameter(K::PAGE_REF_ACCESSOR))
		{
			$pageIds[] = $request->getParameter(K::PAGE_REF_ACCESSOR);
		}
		else if ($request->hasModuleParameter('website', 'id'))
		{
			$pageIds[] = $request->getModuleParameter('website', 'id');
		}
		else if ($request->hasModuleParameter('website', K::COMPONENT_ID_ACCESSOR))
		{
			$pageIds[] = $request->getModuleParameter('website', K::COMPONENT_ID_ACCESSOR);
		}
		else
		{
			$pageIds = parent::getDocumentIdArrayFromRequest($request);
		}
		return $pageIds;
	}
	
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		$website = website_WebsiteModuleService::getInstance()->getCurrentWebsite();	
		if (!$website->isPublished())
		{
			include f_util_FileUtils::buildWebeditPath('site-disabled.php');
			return View::NONE;
		}
			
		controller_ChangeController::setNoCache();
		$this->setContentType('text/html');
		$pageId = $this->getDocumentIdFromRequest($request);
		try
		{
			$page = DocumentHelper::getDocumentInstance($pageId);
			if ($page instanceof website_persistentdocument_pageexternal)
			{
				$context->getController()->redirectToUrl($page->getUrl());
				return View::NONE;
			}
			else if (! $page instanceof website_persistentdocument_page)
			{
				throw new PageException($pageId, PageException::PAGE_NO_ID);
			}
			
			if (!$page->isPublished())
			{
				throw new PageException($pageId, PageException::PAGE_NOT_AVAILABLE);
			}
			else
			{
				if ($page->getUsehttps() != RequestContext::getInstance()->inHTTPS())
				{
					$website = website_WebsiteModuleService::getInstance()->getCurrentWebsite();
					$url = ($page->getUsehttps()) ? 'https://' : 'http://';
					$url .= $website->getDomain() . RequestContext::getInstance()->getPathURI();
					if (Framework::isDebugEnabled())
					{
						Framework::debug(__METHOD__ . ' Bad protocol redirect to : ' . $url);
					}
					header("HTTP/1.1 301 Moved Permanently");
					$context->getController()->redirectToUrl($url);
					return View::NONE;
				}
			}
			
			website_PageService::getInstance()->render($page);
			return View::NONE;
		}
		catch (PageException $e)
		{
			$this->handlePageException($pageId, $e, $request, $context);
		}
		return View::NONE;
	}
	/**
	 * @param Integer $pageId
	 * @param PageException $e
	 * @param Context $context
	 * @param Request $request
	 */
	private function handlePageException($pageId, $e, $request, $context)
	{
		Framework::exception($e);
		$controller = $context->getController();
		// FIXME - INTCOURS - Better PageException mechanism :
		$request->setParameter('message', f_Locale::translate('&modules.website.exception.page-' . $e->getCode() . ';', array('param' => $e->getMessage())));
		Framework::warn(sprintf('[website_DisplayAction] Cannot display requested Page (ID="%d") : %s', $pageId, $request->getParameter('message')));
		switch ($e->getCode())
		{
			case PageException::PAGE_NO_ID :
			case PageException::PAGE_BAD_WEBSITE :
				// No ID or no Handler, 404 :
				$controller->forward('website', 'Error404');
				break;
			default :
				// Misc. issue, Page is Unavailable :
				$controller->forward('website', 'Unavailable');
				break;
		}
	}
	/**
	 * Page display is not secure (except for extranet pages?...).
	 *
	 * @return boolean Always false.
	 */
	public function isSecure()
	{
		return false;
	}
	/**
	 * Traitement absence de permission
	 *
	 * @param String $login
	 * @param String $permission
	 * @param Integer $nodeId
	 */
	protected function onMissingPermission($login, $permission, $nodeId)
	{
		if (Framework::isDebugEnabled())
		{
			Framework::debug(__METHOD__ . " ($login, $permission, $nodeId)");
			Framework::debug(__METHOD__ . " illegalAccessPage : " . $_SERVER["REQUEST_URI"]);
		}
		$user = $this->getContext()->getUser();
		$user->setAttribute('illegalAccessPage', $_SERVER["REQUEST_URI"]);
		$this->getContext()->getController()->forward('website', 'Error401');
	}
	
	/**
	 * @return Boolean
	 */
	protected function suffixSecureActionByDocument()
	{
		$user = users_UserService::getInstance()->getCurrentUser();
		return ($user instanceof users_persistentdocument_backenduser);
	}
	
	public function getRequestMethods()
	{
		return Request::GET | Request::POST;
	}
}