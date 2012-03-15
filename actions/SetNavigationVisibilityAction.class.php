<?php
class website_SetNavigationVisibilityAction extends f_action_BaseAction
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		$docArray   = $this->getDocumentInstanceArrayFromRequest($request);
		$visibility = $request->getParameter('v');

		if ($visibility != website_ModuleService::HIDDEN
		&& $visibility != website_ModuleService::VISIBLE
		&& $visibility != website_ModuleService::HIDDEN_IN_MENU_ONLY
		&& $visibility != website_ModuleService::HIDDEN_IN_SITEMAP_ONLY)
		{
			Framework::debug("website_SetVisibilityAction: 'v' parameter is invalid ('".$visibility."'): set to '".website_ModuleService::VISIBLE."'.");
			$visibility = website_ModuleService::VISIBLE;
		}

		foreach ($docArray as $doc)
		{
			$doc->setNavigationVisibility($visibility);
			$doc->save();
		}

		return self::getSuccessView();
	}
}