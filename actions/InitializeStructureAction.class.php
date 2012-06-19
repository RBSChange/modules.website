<?php
/**
 * website_InitializeStructureAction
 * @package modules.website.actions
 */
class website_InitializeStructureAction extends change_JSONAction
{
	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	public function _execute($context, $request)
	{
		$module = $request->getParameter('moduleName');
		$script = $request->getParameter('scriptName');
		$attributes = $request->getParameter('attributes');
		$container = $this->getDocumentInstanceFromRequest($request);
		website_ModuleService::getInstance()->inititalizeStructure($container, $module, $attributes, $script);
		return $this->sendJSON(array('id' => $container->getId()));
	}
}