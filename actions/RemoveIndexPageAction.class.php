<?php
class website_RemoveIndexPageAction extends change_JSONAction
{
	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	public function _execute($context, $request)
	{
		$topic = $this->getDocumentInstanceFromRequest($request);
		try
		{
			$topic->getDocumentService()->removeIndexPage($topic, true);
			$this->logAction($topic);
		}
		catch (Exception $e)
		{
			Framework::exception($e);
			return $this->sendJSONError(LocaleService::getInstance()->trans('m.website.bo.general.remove-index-page-error'));
		}
		return $this->sendJSON(array('cmpref' => $topic->getId(), 'documentversion' => $topic->getDocumentversion()));
	}
}