<?php
class website_GetPageGroupVersionsAction extends f_action_BaseJSONAction
{
	/**
	 * @see f_action_BaseAction::_execute()
	 *
	 * @param Context $context
	 * @param Request $request
	 */
	protected function _execute ($context, $request) 
	{
		$pageGroup = $this->getPageGroup($request);
		$this->sendJSON($pageGroup->getVersionsInfo());
 	}

 	/**
 	 * @param Request $request
 	 * @return website_persistentdocument_pagegroup
 	 */
	private function getPageGroup($request)
	{
		return $this->getDocumentInstanceFromRequest($request);
	}
	
	/**
	 * @return Boolean by default false
	 */
	protected function isDocumentAction()
	{
		return false;
	}
}