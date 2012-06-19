<?php
class website_EditContentAction extends change_Action
{
	const FORCE_PAGE_RESET = 'force_page_reset';
	
	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	public function _execute($context, $request)
	{
		$document = DocumentHelper::getCorrection($this->getDocumentInstanceFromRequest($request));
		$ds = $document->getDocumentService();
		if (!$document->getContent() || $request->hasParameter(self::FORCE_PAGE_RESET))
		{
			$ds->setDefaultContent($document);
		}
		$request->setAttribute('document', $document);
		return change_View::SUCCESS;
	}
}