<?php
/**
 * website_BlockIncompatibiltybrowserAction
 * @package modules.website.lib.blocks
 */
class website_BlockIncompatibiltybrowserAction extends website_BlockAction
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
			return website_BlockView::NONE;
		}
		
		$rc = RequestContext::getInstance();
		$browserVersion = $rc->getUserAgentTypeVersion();
		if ($rc->getUserAgentType() == 'trident' && $browserVersion !== 'all' && intval($browserVersion) <= 4)
		{
			return website_BlockView::SUCCESS;
		}
		return website_BlockView::NONE;
	}
}