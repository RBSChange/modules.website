<?php
class website_RichtextConfigAction extends change_Action
{
	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	public function _execute($context, $request)
	{
		$configSet = $request->getParameter('configset', 'ChangeDefault');
		$subset = $request->getParameter('subset');
			
		$request->setAttribute('subset', $subset);
		$request->setAttribute('configset', $configSet);
		return change_View::SUCCESS;
	}

	/**
	 * @return boolean
	 */
	public function isSecure()
	{
		return false;
	}
}