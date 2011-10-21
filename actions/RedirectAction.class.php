<?php
/**
 * website_RedirectAction
 * @package modules.website.actions
 */
class website_RedirectAction extends change_Action
{
	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	public function _execute($context, $request)
	{
		
		$redirectType = $request->getParameter('redirectType', 301);
		$location = $request->getParameter('location');		
		if (Framework::isInfoEnabled())
		{ 
			Framework::info(__METHOD__ . '(' . $location . ', ' . $redirectType . ')');
		}
		
		if (empty($location))
		{
			Framework::warn(__METHOD__ .' location note defined.');
			$context->getController()->forward('website', 'Unavailable');
		}
		
		f_web_http_Header::setStatus($redirectType);
		header("Location: ".$location);
		echo '<html><head><meta http-equiv="refresh" content="0;url=', $location, '"/></head></html>';	
		return change_View::NONE;
	}
	
	/**
	 * @see f_action_BaseAction::isSecure()
	 *
	 * @return boolean
	 */
	public function isSecure()
	{
		return false;
	}
}