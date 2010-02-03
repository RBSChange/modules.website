<?php
class website_PreviewPageAction extends website_Action
{
    /**
     * Request accessor for the page content.
     *
     */
    const CONTENT_ACCESSOR = 'content';
    /**
     * @param Context $context
     * @param Request $request
     */
    public function _execute ($context, $request)
    {
        if ($request->hasParameter(K::COMPONENT_ID_ACCESSOR) && $request->hasParameter(self::CONTENT_ACCESSOR))
        {
            try
            {
            	$page = $this->getDocumentInstanceFromRequest($request);
            	$content = website_PageService::getInstance()->getCleanContent($request->getParameter(self::CONTENT_ACCESSOR));
            	$page->setContent($content);
                if (Framework::isInfoEnabled())
                {
                    Framework::info(__CLASS__ . ' Generate preview for page id : ' . $page->getId());
                }
                
                ob_start();      
               	website_PageService::getInstance()->render($page);
                $HTMLPage = ob_get_clean();
                $message = $this->getPreviewUrl($HTMLPage);
                if (Framework::isInfoEnabled())
                {
                    Framework::info(__CLASS__ . ' Preview generated at : ' . $message);
                }
                $request->setAttribute('message', $message);   
            } 
            catch (Exception $e)
            {
                Framework::exception($e);
                return self::getErrorView();
            }
        }
        return self::getSuccessView();
    }
    /**
     * Get Preview cache URL
     *
     * @param integer $id Preview ID
     * @return string URL
     */
    private function getPreviewUrl ($content)
    {
        $md5 = md5($content);
        $relPath = 'cache/htmlpreview/preview-' . $md5 . '.html';
        $filePath = f_util_FileUtils::buildWebappPath('www', $relPath);
        f_util_FileUtils::writeAndCreateContainer($filePath, $content);
        return Framework::getUIBaseUrl() . '/'.$relPath;
    }
}