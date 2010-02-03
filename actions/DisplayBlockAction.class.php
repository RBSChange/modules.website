<?php
class website_DisplayBlockAction extends f_action_BaseJSONAction
{
	/**
	 * type=modules_website_iframe
	 * &display[url]=about%3Ablank&display[width]=640&display[height]=480&display[scrolling]=auto&display[text]=
	 * &pageid=877
	 * &pagelang=fr
	 * &lang=fr
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		try
		{		
			$ps = website_PageService::getInstance();
			$page = $ps->getDocumentInstance($request->getParameter('pageid'));
			
			$blocType = $request->getParameter('type');
			$componentLang = $request->getParameter(K::COMPONENT_LANG_ACCESSOR);
			$displayParam = $request->getParameter('display', array());
			if (!is_array($displayParam)) {$displayParam = array();}
			
			$blockInfo = $ps->buildBlockInfo($blocType, $displayParam, $componentLang);
			$blockContent = $ps->getBlockContentForEdition($page, $blockInfo);
						
		}
		catch (Exception $e)
		{
			Framework::exception($e);
			return $this->sendJSONException($e);
		}
		return $this->sendJSON(array('message' => $blockContent));
	}
}
