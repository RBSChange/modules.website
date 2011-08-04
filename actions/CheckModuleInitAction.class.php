<?php
/**
 * website_CheckModuleInitAction
 * @package modules.website.actions
 */
class website_CheckModuleInitAction extends change_JSONAction
{
	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	public function _execute($context, $request)
	{
		$result = website_ModuleService::getInstance()->checkInitModuleInfos();	
		return $this->sendJSON($result);
	}
}