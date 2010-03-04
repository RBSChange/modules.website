<?php
class website_BlockSimplecontentAction extends website_BlockAction
{
	/**
	 * @see website_BlockAction::execute()
	 *
	 * @param website_BlockActionRequest $request
	 * @param website_BlockActionResponse $response
	 * @return String
	 */
	function execute($request, $response)
	{
		$viewName = $this->getConfiguration()->getView();
		if (StringUtils::isEmpty($viewName))
		{
			throw new Exception("Block website_simplecontent: missing view parameter");
		}
		$viewInfo = explode('/', $viewName);
		if (count($viewInfo) == 1)
		{
			$module = "website";
			$templateName = $viewName;
		}
		else
		{
			$module = $templateInfo[0];
			$viewName = $templateInfo[1];
		}
		$templateName = ucfirst($module) .'-Block-Simplecontent-' . $viewName;

		$website = website_WebsiteModuleService::getInstance()->getCurrentWebsite();
		$request->setAttribute("website", $website);

		return $this->getTemplateByFullName("modules_".$module, $templateName);
	}
}
