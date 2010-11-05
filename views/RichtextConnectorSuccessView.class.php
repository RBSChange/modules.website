<?php
class website_RichtextConnectorSuccessView extends f_view_BaseView
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		$this->setTemplateName('RichtextConnector-FileUpload', K::HTML);
		$this->setAttribute('errorNumber', $request->getParameter('errorNumber'));
		$this->setAttribute('customMsg', $request->getParameter('customMsg', ''));
		$this->setAttribute('url', $request->getParameter('url', ''));
		$this->setAttribute('fileName', $request->getParameter('fileName', ''));
	}
}
