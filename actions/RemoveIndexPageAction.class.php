<?php
class website_RemoveIndexPageAction extends f_action_BaseJSONAction
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		$topic = $this->getDocumentInstanceFromRequest($request);
		try
		{
		    website_WebsiteModuleService::getInstance()->removeIndexPage($topic, true);
		    $this->logAction($topic);
		}
		catch (Exception $e)
		{
			Framework::exception($e);
			return $this->sendJSONError(f_Locale::translateUI('&modules.website.bo.general.remove-index-page-error;'));
		}
		return $this->sendJSON(array('cmpref' => $topic->getId(), 'documentversion' => $topic->getDocumentversion()));
	}
}