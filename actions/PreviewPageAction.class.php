<?php
class website_PreviewPageAction extends change_JSONAction
{
	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	public function _execute($context, $request)
	{
		if ($request->hasParameter('cmpref') && $request->hasParameter('content'))
		{
			$page = DocumentHelper::getDocumentInstance($this->getDocumentIdFromRequest($request), 'modules_website/page');
			$content =  website_PageService::getInstance()->getCleanContent($request->getParameter('content'));
			$page->setContent($content);
			if (Framework::isInfoEnabled())
			{
				Framework::info(__CLASS__ . ' Generate preview for page id : ' . $page->getId());
				Framework::info($content);
			}
			
			ob_start();
			website_PageService::getInstance()->render($page);
			$HTMLPage = ob_get_clean();
			$message = $this->getPreviewUrl($HTMLPage);
			if (Framework::isInfoEnabled())
			{
				Framework::info(__CLASS__ . ' Preview generated at : ' . $message);
			}
			return $this->sendJSON(array('message' => $message));
		}
		else
		{
			throw new Exception('Unable to generate preview content');
		}
	}
	
	/**
	 * Get Preview cache URL
	 *
	 * @param integer $id Preview ID
	 * @return string URL
	 */
	private function getPreviewUrl($content)
	{
		$md5 = md5($content);
		$fileName = 'preview-' . $md5 . '.html';
		$filePath = f_util_FileUtils::buildWebCachePath('htmlpreview', $fileName);
		f_util_FileUtils::writeAndCreateContainer($filePath, $content);
		
		$relPath = '/cache/www/htmlpreview/';
		return Framework::getUIBaseUrl() . $relPath . $fileName;
	}
}