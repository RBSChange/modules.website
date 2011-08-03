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

		$<{$documentModel->getDocumentName()}> = $this->getDocumentParameter(K::COMPONENT_ID_ACCESSOR, "<{$documentModel->getDocumentClassName()}>");
		if ($<{$documentModel->getDocumentName()}> === null)
		{
			// We choose not to throw anything if document parameter is missing
			return null;
		}

		// We transmit the document to the view
		$request->setAttribute('<{$documentModel->getDocumentName()}>', $<{$documentModel->getDocumentName()}>);
		
		if (!$<{$documentModel->getDocumentName()}>->isPublished())
		{
			// Un-published documents must not be seen in front-office
			return $this->genericView('Unavailable');
		}

		return website_BlockView::SUCCESS;
	}
}