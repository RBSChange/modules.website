<?php
/**
 * <{$module}>_Block<{$blockName}>Action
 * @package modules.<{$module}>.lib.blocks
 */
class <{$module}>_Block<{$blockName}>Action extends <{if $genTag }>website_TaggerBlockAction<{else}>website_BlockAction<{/if}>

{
	/**
	 * @see website_BlockAction::execute()
	 *
	 * @param f_mvc_Request $request
	 * @param f_mvc_Response $response
	 * @return String
	 */
	function execute($request, $response)
	{
		if ($this->isInBackoffice())
		{
			// We choose to do nothing in back-office
			return null;
		}
		
		// Get published documents
		$orderProperty = $request->getParameter("orderBy", "label");
		$orderDirection = $request->getParameter("orderByDirection", "asc");
		$order = Order::byString($orderProperty, $orderDirection);
		$request->setAttribute("orderBy", $orderProperty);
		$request->setAttribute("orderByDirection", $orderDirection);
		
		$<{$documentModel->getDocumentName()}>Service = <{$module}>_<{$documentModel->getDocumentName()|ucfirst}>Service::getInstance();
		$<{$documentModel->getDocumentName()}>s = $<{$documentModel->getDocumentName()}>Service->getPublished($order);
		
		// <{$documentModel->getDocumentName()}>s pagination
		$pageIndex = $request->getParameter("page", 1);
		$itemPerPage = $this->getConfigurationParameter("itemPerPage", 10);
		$paginator = new paginator_Paginator("<{$documentModel->getDocumentName()}>s", $pageIndex, $<{$documentModel->getDocumentName()}>s, $itemPerPage);
		
		// Transmit to the view
		$request->setAttribute("<{$documentModel->getDocumentName()}>s", $paginator);

		return website_BlockView::SUCCESS;
	}
}