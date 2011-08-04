<?php
abstract class website_ErrorAction extends change_Action
{
	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	public function _execute($context, $request)
	{
		f_web_http_Header::setStatus($this->getStatus());
		try
		{
			$page = $this->getPage();
			if ($page === null)
			{
				throw new Exception("No page was found");
			}
			if (!$page->isPublished())
			{
				throw new PageException(PageException::PAGE_NOT_AVAILABLE);
			}
			$request->setParameter(K::PAGE_REF_ACCESSOR, $page->getId());
			$context->getController()->forward('website', 'Display');
		}
		catch (Exception $e)
		{
			Framework::exception($e);
		}
		return change_View::NONE;
	}

	/**
	 * @return Integer
	 */
	abstract protected function getStatus();

	/**
	 * @return website_persistentdocument_page
	 */
	abstract protected function getPage();

	/**
	 * @return boolean
	 */
	public function isSecure()
	{
		return false;
	}
}