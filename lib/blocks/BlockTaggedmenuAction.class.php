<?php

class website_BlockTaggedmenuAction extends website_BlockAction
{
	/**
	 * @see f_mvc_Action::getCacheDependencies()
	 *
	 * @return array<string>
	 */
	public function getCacheDependencies()
	{
		return array("modules_website/menu",
					 "modules_website/menuitem",
					 "modules_website/menuitemdocument",
					 "modules_website/menuitemfunction",
					 "modules_website/menuitemtext",
					 "modules_website/page",
		 			 "modules_website/pagegroup",
					 "modules_website/pageexternal",
					 "modules_website/pagereference",
					 "modules_website/pageversion",
		 			 "modules_website/topic",
		 			 "modules_website/systemtopic",
					 "modules_website/website",
					 "tags/contextual_website_website_menu*");
	}

	/**
	 * @param website_BlockActionRequest $request
	 * @return array<mixed>
	 */
	public function getCacheKeyParameters($request)
	{
		return array("context->id" => $this->getPage()->getId(),
			"lang->id" => RequestContext::getInstance()->getLang(),
			"template" => $this->getConfigurationParameter('template'),
			"tag" => $this->getConfigurationParameter('tag'),
			"depth" => $this->getConfigurationParameter('depth'),
			"class" => $this->getConfigurationParameter('class'),
			"id" => $this->getConfigurationParameter('id'),
			"separator" => $this->getConfigurationParameter('separator')
		);
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
		$tag = $this->getConfigurationParameter('tag');
		if (empty($tag))
		{
			return null;
		}

		try
		{
			$wsModuleService = website_WebsiteModuleService::getInstance();
			$website = $wsModuleService->getCurrentWebsite();
			$menu = TagService::getInstance()->getDocumentByContextualTag($tag, $website);
			$depth = $this->getConfigurationParameter('depth');
			if ($depth === null)
			{
				$depth = -1;
			}
			else
			{
				$depth = intval($depth);
			}

			$deployOnlyCurrentPath = $this->getConfigurationParameter('deployonlycurrentpath') == 'true';
			$numberOfCollapseLevel = $this->getConfigurationParameter('collapselevel', 1);

			$page = $this->getPage()->getPersistentPage();
			$request->setAttribute('currentPage', $page);
			$request->setAttribute('currentWebsite', $website);

			if ($deployOnlyCurrentPath)
			{
				$menuObject = $wsModuleService->getRestrictedMenu($menu, $depth, $numberOfCollapseLevel);
			}
			else
			{
				$menuObject = $wsModuleService->getMenu($menu, $depth);
			}
			$request->setAttribute('menuObject', $menuObject);
			$request->setAttribute("masterUlClass", $this->getConfigurationParameter('class'));
			$request->setAttribute("masterUlId", $this->getConfigurationParameter('id'));
			$request->setAttribute('separator', $this->getConfigurationParameter('separator'));
		}
		catch (TagException $e)
		{
			$request->setAttribute('menuObject', false);
		}

		$template = $this->getConfigurationParameter('template');
		if (empty($template))
		{
			return $this->getTemplateByFullName("modules_website", "Website-Menu");
		}
		return ucfirst($template);
	}
}
