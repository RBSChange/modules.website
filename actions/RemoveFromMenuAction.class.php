<?php
class website_RemoveFromMenuAction extends f_action_BaseJSONAction
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		$document = $this->getDocumentInstanceFromRequest($request);
		$deleteCount = website_MenuitemdocumentService::getInstance()->deleteByDocument($document);
		return $this->sendJSON(array("deleteCount" => $deleteCount));
	}
}