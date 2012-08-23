<?php
/**
 * @package modules.<{$module}>
 * @method <{$module}>_Block<{$blockName}>Configuration getConfiguration()
 */
class <{$module}>_Block<{$blockName}>Action extends website_BlockAction
{
	/**
	 * @param f_mvc_Request $request
	 * @param f_mvc_Response $response
	 * @return String
	 */
	public function execute($request, $response)
	{
		if ($this->isInBackofficeEdition())
		{
			return website_BlockView::NONE;
		}
		
		$count = $this->getDocumentCount($request);
		$request->setAttribute('count', $count);
		
		$itemsPerPage = $this->getConfiguration()->getItemsPerPage();
		$pageNumber = $request->getParameter('page');
		if (!is_numeric($pageNumber) || $pageNumber < 1 || $pageNumber > ceil($count / $itemsPerPage))
		{
			$pageNumber = 1;
		}
		$offset = ($pageNumber - 1) * $itemsPerPage;
		$this->getContext()->addCanonicalParam('page', $pageNumber > 1 ? $pageNumber : null, $this->getModuleName());
		
		if ($count > 0)
		{
			$docs = $this->getDocumentArray($request, $pageNumber, $itemsPerPage);
			$paginator = new paginator_Paginator($this->getModuleName(), $pageNumber, $docs, $itemsPerPage, $count);
			$request->setAttribute('docs', $paginator);
		}
		
		return $this->getConfiguration()->getDisplayMode();
	}
	
	/**
	 * @param f_mvc_Request $request
	 * @return integer
	 */
	protected function getDocumentCount($request)
	{
		// TODO: Get complete document count.
		return 0;
	}
	
	/**
	 * @param f_mvc_Request $request
	 * @param integer $pageNumber
	 * @param integer $itemsPerPage
	 * @return <{$module}>_persistentdocument_<{$documentModel->getDocumentName()}>[]
	 */
	protected function getDocumentArray($request, $pageNumber, $itemsPerPage)
	{
		$offset = ($pageNumber - 1) * $itemsPerPage;
		// TODO: Get the documents for the current page.
		return array();
	}
}