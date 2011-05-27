<?php

class website_UnavailableAction extends f_action_BaseAction 
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		if (Framework::isInfoEnabled())
		{ 
			Framework::info(f_util_ProcessUtils::getBackTrace());
		}
		f_web_http_Header::setStatus(503);
		require(f_util_FileUtils::buildWebeditPath("site-disabled.php"));
		return View::NONE;
	}
	
	public function isSecure()
	{
		return false;
	}
}
