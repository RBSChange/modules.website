<?php
class website_BlockThreadAction extends website_BlockAction
{
	const THREAD_CTX_ATTR = "__thread__";
	
	/**
	 * @return boolean
	 */
	public function isCacheEnabled()
	{
		if ($this->getContext()->hasAttribute(self::THREAD_CTX_ATTR))
		{
			return false;
		}
		return parent::isCacheEnabled();
	}

	/**
	 * @param website_BlockActionRequest $request
	 * @return array<mixed>
	 */
	public function getCacheKeyParameters($request)
	{
		return array("context->label" => $this->getPage()->getNavigationtitle());
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