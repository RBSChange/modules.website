<?php
/**
 * website_BlockAsynchContentAction
 * @package modules.website.actions
 */
class website_BlockAsynchContentAction extends f_action_BaseAction
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		$pageId = $this->getDocumentIdFromRequest($request);
		try 
		{
			$page = website_persistentdocument_page::getInstanceById($pageId);
			website_WebsiteModuleService::getInstance()->setCurrentPageId($page->getId());
			website_PageRessourceService::getInstance()->setGlobalTemplateName('PopIn-ContentBasis');
		
			$fromURL = $request->getParameter('fromURL', (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null);  
			RequestContext::getInstance()->setAjaxMode(true, $fromURL);

			$blockId = $request->getParameter('blockId');
			if (is_array($blockId))
			{
				$json = website_PageService::getInstance()->getRenderedBlock($page, $blockId);
			}
			else
			{
				$blockId = (is_string($blockId) && f_util_StringUtils::isNotEmpty($blockId)) ? array($blockId): array();		
				$moduleName = $request->getParameter('blockModule');			
				$section = $request->getParameter('section', 'All');
				
				$moduleParams = $request->getParameter($moduleName. 'Param');
				if (!is_array($moduleParams)) {$moduleParams = array();}
				$moduleParams[$blockId.'_section'] = $section;
				$request->setParameter($moduleName. 'Param', $moduleParams);
				
				$result = website_PageService::getInstance()->getRenderedBlock($page, $blockId);
				if (isset($result[$blockId]))
				{
					$json = array($section => $result[$blockId]);
				}
				else
				{
					$json = array($section => "<div>Block: $blockId not found</div>");
				}
			}
		}
		catch (Exception $e)
		{
			$json = array('exception' => $e->getMessage());
		}
		controller_ChangeController::setNoCache();
		header('Content-Type: application/json; charset=utf-8');
		echo JsonService::getInstance()->encode($json);		
		return View::NONE;
	}
	
	/**
	 * @return boolean Always false.
	 */
	public function isSecure()
	{
		return false;
	}
}