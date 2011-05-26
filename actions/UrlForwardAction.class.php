<?php
class website_UrlForwardAction extends f_action_BaseAction
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
    {
    	$urlToForward = $request->getParameter('urlToDecode');
    	$request->removeParameter('urlToDecode');
    	unset($_GET['urlToDecode']);
    	unset($_GET['module']); 
    	unset($_GET['action']); 
    	$host = $_SERVER['HTTP_HOST'];
    	list($moduleName, $actionName) = website_UrlRewritingService::getInstance()->getActionToforward($urlToForward, $host, $request);
    	$request->setParameter('module', $moduleName);
    	$request->setParameter('action', $actionName);
    	$context->getController()->forward($moduleName, $actionName);
    }

    public function getRequestMethods()
    {
    	return Request::GET | Request::POST;
    }

    /**
     * @return boolean
     */
    public function isSecure()
    {
        return false;
    }
}
