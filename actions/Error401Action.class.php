<?php
/**
 * @package modules.website
 */
class website_Error401Action extends website_ErrorAction
{
	/**
	 * @return integer 401
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
		$website = website_WebsiteService::getInstance()->getCurrentWebsite();
		return TagService::getInstance()->getDocumentByContextualTag('contextual_website_website_error401-1', $website);
	}
	
	/**
	 * @param change_Context $context
	 * @param change_Request $request
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
}