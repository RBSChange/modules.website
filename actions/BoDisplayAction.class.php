<?php
class website_BoDisplayAction extends change_Action
{

    /**
     * @param change_Context $context
     * @param change_Request $request
     */
    public function _execute ($context, $request)
    {
    	change_Controller::setNoCache();      
        try
        {
        	$document = $this->getDocumentInstanceFromRequest($request);
        	$page = ($request->getParameter('ignoreCorrection') === 'true') ? $document : DocumentHelper::getCorrection($document);
 
        	if ($page instanceof website_persistentdocument_pageexternal)
        	{
        		$context->getController()->redirectToUrl($page->getUrl());
        		return change_View::NONE;
        	} 
        	else if (!$page instanceof website_persistentdocument_page)
        	{
        		 throw new Exception('Invalid document Id');
        	}
            
        	website_PageService::getInstance()->render($page); 	
        	return change_View::NONE;            
        } 
        catch (Exception $e)
        {
        	Framework::exception($e);
            $controller = $context->getController();
            $controller->forward('website', 'Error404');
        }
        return change_View::NONE;
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