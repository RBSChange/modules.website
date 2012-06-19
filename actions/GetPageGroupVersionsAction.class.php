<?php
class website_GetPageGroupVersionsAction extends change_JSONAction
{
	/**
	 * @see f_action_BaseAction::_execute()
	 *
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	protected function _execute ($context, $request) 
	{
		$pageGroup = $this->getPageGroup($request);
		$this->sendJSON($pageGroup->getVersionsInfo());
 	}

 	/**
 	 * @param change_Request $request
 	 * @return website_persistentdocument_pagegroup
 	 */
	private function getPageGroup($request)
	{
		return $this->getDocumentInstanceFromRequest($request);
	}
	
	/**
	 * @return boolean by default false
	 */
	protected function isDocumentAction()
	{
		return false;
	}
}