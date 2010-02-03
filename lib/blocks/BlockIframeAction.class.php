<?php
/**
 * website_BlockIframeAction
 * @package modules.website.lib.blocks
 */
class website_BlockIframeAction extends website_BlockAction
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
			return website_BlockView::BACKOFFICE;
		}
		return website_BlockView::SUCCESS;
	}
}