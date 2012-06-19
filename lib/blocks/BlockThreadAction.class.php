<?php
class website_BlockThreadAction extends website_BlockAction
{
	/**
	 * @param website_BlockActionRequest $request
	 * @return array<mixed>
	 */
	public function getCacheKeyParameters($request)
	{
		return array("context->title" => $this->getContext()->getTitle());
	}

	/**
	 * @param website_BlockActionRequest $request
	 * @param website_BlockActionResponse $response
	 * @return string
	 */
	public function execute($request, $response)
	{
		$pageContext = $this->getContext();
		$breadcrumb = website_PageService::getInstance()->getDefaultBreadcrumb($pageContext);
		$request->setAttribute('breadcrumb', $breadcrumb);
		return website_BlockView::SUCCESS;
	}
}