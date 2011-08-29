<?php
abstract class website_ErrorAction extends change_Action
{
	protected static $called = false;
	
	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	public function _execute($context, $request)
	{
		f_web_http_Header::setStatus($this->getStatus());
		if (self::$called)
		{
			$e = $request->getAttribute(change_Action::EXCEPTION_KEY);
			if (!($e instanceof Exception))
			{
				$e = new Exception('Recurcive Error call');
			}
			$r = new exception_HtmlRenderer();
			$r->printStackTrace($e);
			return change_View::NONE;
		}
		
		self::$called = true;
		$page = $this->getPage();
		if ($page === null)
		{
			throw new Exception("No page was found");
		}
		if (!$page->isPublished())
		{
			throw new PageException(PageException::PAGE_NOT_AVAILABLE);
		}
		$request->setParameter('pageref', $page->getId());
		$context->getController()->forward('website', 'Display');
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