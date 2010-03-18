<?php
class website_BlockThreadAction extends website_BlockAction
{
	const THREAD_CTX_ATTR = "__thread__";
	/**
	 * @see f_mvc_Action::getCacheDependencies()
	 *
	 * @return array<string>
	 */
	public function getCacheDependencies()
	{
		if ($this->getContext()->hasAttribute(self::THREAD_CTX_ATTR))
		{
			return null;
		}
		return array("modules_website/page", "modules_website/pagegroup",
			"modules_website/pagereference", "modules_website/pageversion", 
			"modules_website/topic", "modules_website/systemtopic", "modules_website/website");
	}

	/**
	 * @param website_BlockActionRequest $request
	 * @return array<mixed>
	 */
	public function getCacheKeyParameters($request)
	{
		if ($this->getContext()->hasAttribute(self::THREAD_CTX_ATTR))
		{
			return null;
		}
		return array("context->id" => $this->getPage()->getId(),
			"context->label" => $this->getPage()->getNavigationtitle(), 
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
		$pageContext = $this->getContext();
		if ($pageContext->hasAttribute(self::THREAD_CTX_ATTR))
		{
			$breadcrumb = $pageContext->getAttribute(self::THREAD_CTX_ATTR);
		}
		else
		{
			$breadcrumb = website_PageService::getInstance()->getDefaultBreadcrumb($pageContext);
		}
		$request->setAttribute('breadcrumb', $breadcrumb);
		return website_BlockView::SUCCESS;
	}
}