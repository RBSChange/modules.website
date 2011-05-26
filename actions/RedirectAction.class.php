<?php
/**
 * website_RedirectAction
 * @package modules.website.actions
 */
class website_RedirectAction extends f_action_BaseAction
{
	/**
	 * @param Context $context
	 * @param Request $request
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
		return View::NONE;
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