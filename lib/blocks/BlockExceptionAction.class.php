<?php
class website_BlockExceptionAction extends website_BlockAction
{
	/**
	 * @see website_BlockAction::execute()
	 *
	 * @param website_BlockActionRequest $request
	 * @param website_BlockActionResponse $response
	 * @return String
	 */
	function execute($request, $response)
	{
		if ($this->isInBackoffice())
		{
			return website_BlockView::NONE;
		}
		
		$globalRequest = change_Controller::getInstance()->getContext()->getRequest();
		if ($globalRequest->hasAttribute(change_Action::EXCEPTION_KEY))
		{
			$exception = $globalRequest->getAttribute(change_Action::EXCEPTION_KEY);
			$request->setAttribute("exception", $exception);
		}
		
		if ($request->hasAttribute("exception"))
		{
			Framework::exception($request->getAttribute("exception"));
		}
		
		$request->setAttribute("devMode", Framework::inDevelopmentMode());
		
		return website_BlockView::SUCCESS;
	}
}