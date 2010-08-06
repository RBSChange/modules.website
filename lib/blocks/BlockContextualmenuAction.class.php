<?php

class website_BlockContextualmenuAction extends website_BlockAction
{
	/**
	 * @see f_mvc_Action::getCacheDependencies()
	 *
	 * @return array
	 */
	public function getCacheDependencies()
	{
		return array(
		 "modules_website/page",
		 "modules_website/pagegroup",
		 "modules_website/pageexternal",
		 "modules_website/pagereference",
		 "modules_website/pageversion",
		 "modules_website/topic",
		 "modules_website/systemtopic",
		 "modules_website/website");
	}

	/**
	 * @param f_mvc_Request $request
	 */
	public function getCacheKeyParameters($request)
	{
		$page = $this->getPage()->getPersistentPage();
		return array("context->id" => ($page != null) ? $page->getId() : time(),
			"lang->id" => RequestContext::getInstance()->getLang(),
			"class" => $this->getConfigurationParameter('class'),
			"id" => $this->getConfigurationParameter('id'),
			"template" => $this->getConfigurationParameter('template'),
			"separator" => $this->getConfigurationParameter('separator'),
			"depth" => $this->getConfigurationParameter('depth'),
			"deployall" => $this->getConfigurationParameter('deployall'));
	}

	/**
	 * @see website_BlockAction::execute()
	 *
	 * @param website_BlockActionRequest $request
	 * @param website_BlockActionResponse $response
	 * @return String
	 */
	function execute($request, $response)
	{
		$startwebsitelevel = intval($this->getConfigurationParameter('startwebsitelevel'));

		$depth = intval($this->getConfigurationParameter('depth'));
		$deployall = $this->getConfigurationParameter('deployall') == 'true';
		 
		$page = $this->getPage()->getPersistentPage();
		$website = website_WebsiteModuleService::getInstance()->getCurrentWebsite();
		if ($startwebsitelevel <= 0)
		{
			$rootElement = null;
		}
		else
		{
			$ancestors = $this->getPage()->getAncestors();
			$ancestors[] = $page->getId();
			if (count($ancestors) > $startwebsitelevel)
			{
				$rootElement = DocumentHelper::getDocumentInstance($ancestors[$startwebsitelevel]);
			}
			else
			{
				return website_BlockView::NONE;
			}
		}

		if ($deployall)
		{
			$menuObject = website_WebsiteModuleService::getInstance()->getContextMenu($rootElement, $depth);
		}
		else
		{
			$menuObject = website_WebsiteModuleService::getInstance()->getRestrictedContextMenu($rootElement, $depth);
		}
		$request->setAttribute('rootElement', website_WebsiteModuleService::getInstance()->getParentNodeFromDocument($rootElement));
		$request->setAttribute('menuObject', $menuObject);
		$request->setAttribute('currentPage', $page);
		$request->setAttribute('currentWebsite', $website);
		$request->setAttribute('separator', $this->getConfigurationParameter('separator'));
		$request->setAttribute("masterUlClass", $this->getConfigurationParameter('class'));
		$request->setAttribute("masterUlId", $this->getConfigurationParameter('id'));
		$template = $this->getConfigurationParameter('template');
		if (empty($template))
		{
			return $this->getTemplateByFullName("modules_website", "Website-Menu");
		}

		return ucfirst($template);
	}
}
