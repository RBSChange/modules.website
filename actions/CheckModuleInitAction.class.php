<?php
/**
 * website_CheckModuleInitAction
 * @package modules.website.actions
 */
class website_CheckModuleInitAction extends f_action_BaseJSONAction
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		$result = website_ModuleService::getInstance()->checkInitModuleInfos();	
		return $this->sendJSON($result);
	}
}