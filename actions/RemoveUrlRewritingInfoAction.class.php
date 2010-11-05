<?php
class website_RemoveUrlRewritingInfoAction extends f_action_BaseAction
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
