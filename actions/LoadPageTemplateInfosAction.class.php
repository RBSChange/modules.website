<?php
/**
 * website_LoadPageTemplateInfosByContainerAction
 * @package modules.website.actions
 */
class website_LoadPageTemplatesInfosByContainerAction extends f_action_BaseJSONAction
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		$result = array();

		$documentId = intval($request->getParameter('containerId'));
		$templates = theme_ModuleService::getInstance()->getAllowedTemplateForDocumentId($documentId);			
		
		$result = array();
		foreach ($templates as $template)
		{
			$item = array(
				'label' => $template->getLabel(),
				'codename' => $template->getCodename(),
				'id' => $template->getId(),
				'hasPreviewImage' => ($template->getThumbnail() !== null)
			);
			$result[] = $item;
		}

		return $this->sendJSON($result);
	}
}