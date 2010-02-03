<?php
class website_RichtextConfigAction extends website_Action
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		$configSet = $request->getParameter('configset', 'ChangeDefault');
		$subset = $request->getParameter('subset');
			
		$request->setAttribute('subset', $subset);
		$request->setAttribute('configset', $configSet);
		return View::SUCCESS;
	}


	public function isSecure()
	{
		return false;
	}
}