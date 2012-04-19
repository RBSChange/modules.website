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
		$page = website_persistentdocument_page::getInstanceById($pageId);
		website_WebsiteModuleService::getInstance()->setCurrentPageId($page->getId());
		$moduleName = $request->getParameter('blockModule');
		$blockId = $request->getParameter('blockId');
		$section = $request->getParameter('section', 'All');
		
		$moduleParams = $request->getParameter($moduleName. 'Param');
		if (!is_array($moduleParams)) {$moduleParams = array();}
		$moduleParams[$blockId.'_section'] = $section;
		$request->setParameter($moduleName. 'Param', $moduleParams);
		
		website_PageRessourceService::getInstance()->setGlobalTemplateName('PopIn-ContentBasis');
		$result = website_PageService::getInstance()->getRenderedBlock($page);
		if (isset($result[$blockId]))
		{
			$json = array($section => $result[$blockId]);
		}
		else
		{
			$json = array($section => "<div>Block: $blockId not found</div>");
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