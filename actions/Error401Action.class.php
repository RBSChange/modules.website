<?php
/**
 * @package modules.website
 */
class website_Error401Action extends website_ErrorAction
{
	
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		if (users_UserService::getInstance()->getCurrentFrontEndUser() !== null)
		{
			$this->getContext()->getController()->forward('website', 'Error403');
			return null;
		}
		return parent::_execute($context, $request);
	}
	
	/**
	 * @return string
	 */
	protected function getStatus()
	{
		return 401;
	}

	/**
	 * @return website_persistentdocument_page
	 */
	protected function getPage()
	{
		return TagService::getInstance()->getDocumentByContextualTag('contextual_website_website_error401-1', website_WebsiteModuleService::getInstance()->getCurrentWebsite());
	}
}