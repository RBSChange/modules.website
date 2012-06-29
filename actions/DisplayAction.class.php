<?php
class website_DisplayAction extends change_Action
{
	protected function getDocumentIdArrayFromRequest($request)
	{
		$pageIds = array();
		// Page ID could come in (too) many flavours :
		if ($request->hasParameter('pageref'))
		{
			$pageIds[] = $request->getParameter('pageref');
		}
		else if ($request->hasModuleParameter('website', 'id'))
		{
			$pageIds[] = $request->getModuleParameter('website', 'id');
		}
		else if ($request->hasModuleParameter('website', change_Request::DOCUMENT_ID))
		{
			$pageIds[] = $request->getModuleParameter('website', change_Request::DOCUMENT_ID);
		}
		else
		{
			$pageIds = parent::getDocumentIdArrayFromRequest($request);
		}
		return $pageIds;
	}
	
	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	public function _execute($context, $request)
	{
		$website = website_WebsiteService::getInstance()->getCurrentWebsite();	
		if (!$website->isPublished())
		{
			include f_util_FileUtils::buildProjectPath('site-disabled.php');
			return change_View::NONE;
		}
			
		change_Controller::setNoCache();
		$this->setContentType('text/html');
		$pageId = $this->getDocumentIdFromRequest($request);
		try
		{
			$page = DocumentHelper::getDocumentInstance($pageId);
			if ($page instanceof website_persistentdocument_pageexternal)
			{
				$context->getController()->redirectToUrl($page->getUrl());
				return change_View::NONE;
			}
			else if (! $page instanceof website_persistentdocument_page)
			{
				throw new PageException($pageId, PageException::PAGE_NO_ID);
			}
			
			if (!$page->isPublished())
			{
				throw new PageException($pageId, PageException::PAGE_NOT_AVAILABLE);
			}
			else if ($page->getDocumentService()->getWebsiteId($page) != $website->getId())
			{
				throw new PageException($pageId, PageException::PAGE_NOT_AVAILABLE);
			}
			else
			{
				if ($page->getUsehttps() != RequestContext::getInstance()->inHTTPS())
				{
					$website = website_WebsiteService::getInstance()->getCurrentWebsite();
					$url = ($page->getUsehttps()) ? 'https://' : 'http://';
					$url .= $website->getDomain() . RequestContext::getInstance()->getPathURI();
					if (Framework::isDebugEnabled())
					{
						Framework::debug(__METHOD__ . ' Bad protocol redirect to : ' . $url);
					}
					header("HTTP/1.1 301 Moved Permanently");
					$context->getController()->redirectToUrl($url);
					return change_View::NONE;
				}
			}
			website_PageService::getInstance()->render($page);
			return change_View::NONE;
		}
		catch (PageException $e)
		{
			$this->handlePageException($pageId, $e, $request, $context);
		}
		return change_View::NONE;
	}
	/**
	 * @param integer $pageId
	 * @param PageException $e
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	private function handlePageException($pageId, $e, $request, $context)
	{
		Framework::exception($e);
		$controller = $context->getController();
		$request->setParameter('message', LocaleService::getInstance()->trans('m.website.exception.page-' . $e->getCode(), array(), array('param' => $e->getMessage())));
		if (Framework::isWarnEnabled())
		{
			Framework::warn(__METHOD__ . 'Cannot display requested Page (ID="' . $pageId . '") : ' . $request->getParameter('message'));
		}
		$controller->forward('website', 'Error404');
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
	 * @param string $login
	 * @param string $permission
	 * @param integer $nodeId
	 */
	protected function onMissingPermission($login, $permission, $nodeId)
	{
		if (Framework::isDebugEnabled())
		{
			Framework::debug(__METHOD__ . " ($login, $permission, $nodeId)");
			Framework::debug(__METHOD__ . " users_illegalAccessPage : " . $_SERVER["REQUEST_URI"]);
		}
		change_Controller::getInstance()->getStorage()->writeForUser('users_illegalAccessPage', $_SERVER["REQUEST_URI"]);
		$this->getContext()->getController()->forward('website', 'Error401');
	}
	
	/**
	 * @return boolean
	 */
	protected function isDocumentAction()
	{
		$user = users_UserService::getInstance()->getCurrentUser();
		return ($user instanceof users_persistentdocument_user);
	}
	
	public function getRequestMethods()
	{
		return change_Request::GET | change_Request::POST;
	}
}