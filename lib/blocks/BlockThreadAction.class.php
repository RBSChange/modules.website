<?php
class website_BlockThreadAction extends website_BlockAction
{
	/**
	 * @var integer
	 */
	private $detailId = null;
	
	/**
	 * @return string[string]
	 */
	public function getCacheDependencies()
	{
		if ($this->getDetailId())
		{
			return array($this->getDetailId());
		}
		return array();
	}
	
	/**
	 * @param website_BlockActionRequest $request
	 * @return array<mixed>
	 */
	public function getCacheKeyParameters($request)
	{
		return array("detailId" => $this->getDetailId());
	}
	
	/**
	 * @return integer
	 */
	private function getDetailId()
	{
		if ($this->detailId === null)
		{
			$globalRequest = change_Controller::getInstance()->getRequest();
			if ($this->getContext()->getPersistentPage()->getNavigationVisibility() == website_ModuleService::HIDDEN
				&& $globalRequest->hasParameter('detail_cmpref'))
			{
				$this->detailId = intval($globalRequest->getParameter('detail_cmpref'));
			}
			else
			{
				$this->detailId = 0;
			}
		}
		return $this->detailId;
	}

	/**
	 * @param website_BlockActionRequest $request
	 * @param website_BlockActionResponse $response
	 * @return string
	 */
	public function execute($request, $response)
	{
		$breadcrumb = website_PageService::getInstance()->getDefaultBreadcrumb($this->getContext());
		$request->setAttribute('breadcrumb', $breadcrumb);
		return website_BlockView::SUCCESS;
	}
}