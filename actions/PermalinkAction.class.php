<?php
/**
 * website_PermalinkAction
 * @package modules.website.actions
 */
class website_PermalinkAction extends change_Action
{
	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	public function _execute($context, $request)
	{
		$module = 'website';
		$action = 'Error404';
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