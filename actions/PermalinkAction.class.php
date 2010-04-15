<?php
/**
 * website_PermalinkAction
 * @package modules.website.actions
 */
class website_PermalinkAction extends f_action_BaseAction
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		$module = AG_ERROR_404_MODULE;
		$action = AG_ERROR_404_ACTION;
		$document = null;
		try
		{
			if ($request->hasParameter('cmpref'))
			{
				$document = $this->getDocumentInstanceFromRequest($request);
			}
			else if ($request->hasModuleParameter('website', 'cmpref'))
			{
				$document = DocumentHelper::getDocumentInstance($request->getModuleParameter('website', 'cmpref'));
			}
		}
		catch (Exception $e)
		{
			Framework::exception($e);
		}
	
		if ($document !== null)
		{
			$url = LinkHelper::getDocumentUrl($document);
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: ".$url);
		}
		else
		{
			$context->getController()->forward($module, $action);
		}
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