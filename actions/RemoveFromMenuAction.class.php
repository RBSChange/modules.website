<?php
class website_RemoveFromMenuAction extends change_JSONAction
{
	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	public function _execute($context, $request)
	{
		$document = $this->getDocumentInstanceFromRequest($request);
		$deleteCount = website_MenuitemdocumentService::getInstance()->deleteByDocument($document);
		return $this->sendJSON(array("deleteCount" => $deleteCount));
	}
}