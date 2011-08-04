<?php
/**
 * website_ViewPageexternalAction
 * @package modules.website.actions
 */
class website_ViewPageexternalAction extends change_Action
{
	/**
	 * @param change_Context $context
	 * @param change_Request $request
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
				change_Controller::getInstance()->redirectToUrl($pageexternal->getUrl());
			}
		}
		catch (Exception $e)
		{		
			Framework::exception($e);
		}
		$context->getController()->forward('website', 'Error404');
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