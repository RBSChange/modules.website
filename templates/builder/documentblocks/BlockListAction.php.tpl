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
	public function execute($request, $response)
	{
		if ($this->isInBackoffice())
		{
			return website_BlockView::NULL;
		}
		
		// Get published documents.
		$orderProperty = $request->getParameter('orderBy', 'label');
		$request->setAttribute('orderBy', $orderProperty);
		$orderDirection = $request->getParameter('orderByDirection', 'asc');
		$request->setAttribute('orderByDirection', $orderDirection);
		$order = Order::byString($orderProperty, $orderDirection);
		$<{$documentModel->getDocumentName()}>s = <{$module}>_<{$documentModel->getDocumentName()|ucfirst}>Service::getInstance()->getPublished($order);
		
		// <{$documentModel->getDocumentName()}>s pagination.
		$pageIndex = $request->getParameter('page', 1);
		$itemPerPage = $this->getConfigurationParameter('itemPerPage', 10);
		$paginator = new paginator_Paginator('<{$module}>', $pageIndex, $<{$documentModel->getDocumentName()}>s, $itemPerPage);
		$request->setAttribute('<{$documentModel->getDocumentName()}>s', $paginator);

		return website_BlockView::SUCCESS;
	}
}