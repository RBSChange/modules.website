<?php
class website_RichtextConnectorSuccessView extends change_View
{
	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	public function _execute($context, $request)
	{
		$this->setTemplateName('RichtextConnector-FileUpload', 'html');
		$this->setAttribute('errorNumber', $request->getParameter('errorNumber'));
		$this->setAttribute('customMsg', $request->getParameter('customMsg', ''));
		$this->setAttribute('url', $request->getParameter('url', ''));
		$this->setAttribute('fileName', $request->getParameter('fileName', ''));
	}
}
