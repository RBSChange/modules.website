<?php

class website_UnavailableAction extends f_action_BaseAction 
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		f_web_http_Header::setStatus(503);
		die("<h1>Unavailable</h1>");
	}
	
	public function isSecure()
	{
		return false;
	}
}
