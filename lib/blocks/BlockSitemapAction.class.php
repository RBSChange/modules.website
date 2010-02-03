<?php
class website_BlockSitemapAction extends website_BlockAction
{
	
	/**
	 * @see f_mvc_Action::getCacheDependencies()
	 *
	 * @return array<string>
	 */
	public function getCacheDependencies()
	{
		return array("modules_website/page",
		 "modules_website/pagegroup",
		 "modules_website/pageexternal",
		 "modules_website/pagereference",
		 "modules_website/pageversion",
		 "modules_website/topic",
		 "modules_website/website");
	}

	/**
	 * @param website_BlockActionRequest $request
	 * @return array<mixed>
	 */
	public function getCacheKeyParameters($request)
	{
		return array("column" => $this->findParameterValue("column"),  
			"context->website" => website_WebsiteModuleService::getInstance()->getCurrentWebsite()->getId(),
		    "lang->id" => RequestContext::getInstance()->getLang());
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
        $ws = website_WebsiteModuleService::getInstance();
        $siteMap = $ws->getSitemap($ws->getCurrentWebsite());
        $request->setAttribute('sitemap', $siteMap);
        $column = $this->findParameterValue("column");
        
        if (!empty($column))
        {
        	return website_BlockView::SUCCESS . "Column";
        }
		return website_BlockView::SUCCESS;
	}
}