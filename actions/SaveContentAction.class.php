<?php
class website_SaveContentAction extends change_JSONAction
{
	const PAGE_CONTENT_ACCESSOR = 'content';
	
	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	public function _execute($context, $request)
	{
		$document = $this->getDocumentInstanceFromRequest($request);
		$ds = $document->getDocumentService();
		if ($ds->correctionNeeded($document))
		{
			$document = $ds->createDocumentCorrection($document);
		}
		$document->setDocumentversion($request->getParameter('documentversion'));
		website_PageService::getInstance()->updatePageContent($document, $request->getParameter('content'));
		$ds->save($document);
		$this->logAction($document);
			
		return $this->sendJSON(array('id' => $document->getId(), 
			'documentversion' => $document->getDocumentversion(),
			'lang' => RequestContext::getInstance()->getLang()
		));
	}
	
	/**
	 * Normalize the given XML content.
	 *
	 * @param string $content
	 * @return string
	 */
	public static function normalizeContent($content)
	{
		return $content;
	}
}