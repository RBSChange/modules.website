<?php
class website_BoDisplayAction extends f_action_BaseAction
{

    /**
     * @param Context $context
     * @param Request $request
     */
    public function _execute ($context, $request)
    {
    	controller_ChangeController::setNoCache();      
        try
        {
        	$document = $this->getDocumentInstanceFromRequest($request);
        	$page = ($request->getParameter('ignoreCorrection') === 'true') ? $document : DocumentHelper::getCorrection($document);
 
        	if ($page instanceof website_persistentdocument_pageexternal)
        	{
        		$context->getController()->redirectToUrl($page->getUrl());
        		return View::NONE;
        	} 
        	else if (!$page instanceof website_persistentdocument_page)
        	{
        		 throw new Exception('Invalid document Id');
        	}
            
        	website_PageService::getInstance()->render($page); 	
        	return View::NONE;            
        } 
        catch (Exception $e)
        {
        	Framework::exception($e);
            $controller = $context->getController();
            $controller->forward('website', 'Error404');
        }
        return View::NONE;
    }
    	
	/**
	 * @see f_action_BaseAction::getActionName()
	 * @return String
	 */
	protected function getActionName()
	{
		return 'Display';
	}
	
	/**
	 * @see f_action_BaseAction::isDocumentAction()
	 * @return Boolean
	 */
	protected function isDocumentAction()
	{
		return true;
	}

}