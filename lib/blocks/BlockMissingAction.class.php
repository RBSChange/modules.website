<?php
/**
 * website_BlockMissingAction
 * @package modules.website.lib.blocks
 */
class website_BlockMissingAction extends website_BlockAction
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
		$request->setAttribute('originalClassName', $this->originalClassName);
		return website_BlockView::SUCCESS;
	}
	
	/**
	 * @var String
	 */
	private $originalClassName = null;
	
	/**
	 * @param String $name
	 */
	public function setOriginalClassName($name)
	{
		$this->originalClassName = $name;
	}
}