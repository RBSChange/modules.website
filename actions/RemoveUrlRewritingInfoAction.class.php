<?php
class website_RemoveUrlRewritingInfoAction extends website_Action
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
    {
    	$document = $this->getDocumentInstanceFromRequest($request);
    	$document->getDocumentService()->setUrlRewriting($document, $this->getLang(), null);
		return self::getSuccessView();
    }
}
