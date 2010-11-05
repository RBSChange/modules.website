<?php
/**
 * <{$module}>_Block<{$blockName}>Action
 * @package modules.<{$module}>.lib.blocks
 */
class <{$module}>_Block<{$blockName}>Action extends website_BlockAction
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
		return website_BlockView::SUCCESS;
	}
}