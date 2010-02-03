<?php
class website_SaveTemplateAction extends f_action_BaseJSONAction 
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		$page = DocumentHelper::getDocumentInstance($request->getParameter('pageid'));		
		$template  = website_TemplateService::getInstance()->getNewDocumentInstance();
		$template->setLabel($request->getParameter('label'));
		$template->setDescription($request->getParameter('description'));
		
		$template->setTemplate($page->getTemplate());
		$template->setContent($request->getParameter('content'));
		$sysFolderId = ModuleService::getInstance()->getSystemFolderId('website', 'website');
		$template->save($sysFolderId);
		$this->logAction($template);
		return $this->sendJSON(array('id' => $template->getId(), 'lang' => $template->getLang(), 'label' => $template->getLabel()));
	}
}