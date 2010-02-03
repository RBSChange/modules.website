<?php
/**
 * website_ViewPageexternalAction
 * @package modules.website.actions
 */
class website_ViewPageexternalAction extends f_action_BaseAction
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		try 
		{
			$pageexternal = null;
			if ($request->hasParameter('cmpref'))
			{
				$pageexternal = $this->getDocumentInstanceFromRequest($request);
			}
			else if ($request->hasModuleParameter('website', 'cmpref'))
			{
				$pageexternal = DocumentHelper::getDocumentInstance($request->getModuleParameter('website', 'cmpref'));
			}
			
			if ($pageexternal instanceof website_persistentdocument_pageexternal && $pageexternal->isPublished())
			{
				HttpController::getInstance()->redirectToUrl($pageexternal->getUrl());
			}
		}
		catch (Exception $e)
		{		
			Framework::exception($e);
		}
		$context->getController()->forward(AG_ERROR_404_MODULE, AG_ERROR_404_ACTION);
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