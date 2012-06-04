<?php
/**
 * website_LoadPageTemplateInfosByContainerAction
 * @package modules.website.actions
 */
class website_LoadPageTemplatesInfosByContainerAction extends change_JSONAction
{
	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	public function _execute($context, $request)
	{
		$result = array();

		$documentId = intval($request->getParameter('containerId'));
		$templates = theme_ModuleService::getInstance()->getAllowedTemplateForDocumentId($documentId);			
		
		$pageTemplateDefault = Framework::getConfigurationValue('modules/website/sample/defaultPageTemplate');
		$homeTemplateDefault = Framework::getConfigurationValue('modules/website/sample/defaultHomeTemplate');
		$nosidebarTemplateDefault = Framework::getConfigurationValue('modules/website/sample/defaultNosidebarTemplate');
		$result = array();
		foreach ($templates as $template)
		{
			$item = array(
				'label' => $template->getLabel(),
				'codename' => $template->getCodename(),
				'isPageDefault' => $template->getCodename() === $pageTemplateDefault,
				'isHomeDefault' => $template->getCodename() === $homeTemplateDefault,
				'isNosidebarDefault' => $template->getCodename() === $nosidebarTemplateDefault,
				'id' => $template->getId(),
				'hasPreviewImage' => ($template->getThumbnail() !== null)
			);
			$result[] = $item;
		}

		return $this->sendJSON($result);
	}
}