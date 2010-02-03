<?php
class website_SetNavigationVisibilityAction extends website_Action
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		$docArray   = $this->getDocumentInstanceArrayFromRequest($request);
		$visibility = $request->getParameter('v');

		if ($visibility != WebsiteConstants::VISIBILITY_HIDDEN
		&& $visibility != WebsiteConstants::VISIBILITY_VISIBLE
		&& $visibility != WebsiteConstants::VISIBILITY_HIDDEN_IN_MENU_ONLY
		&& $visibility != WebsiteConstants::VISIBILITY_HIDDEN_IN_SITEMAP_ONLY)
		{
			Framework::debug("website_SetVisibilityAction: 'v' parameter is invalid ('".$visibility."'): set to '".WebsiteConstants::VISIBILITY_VISIBLE."'.");
			$visibility = WebsiteConstants::VISIBILITY_VISIBLE;
		}

		foreach ($docArray as $doc)
		{
			$doc->setNavigationVisibility($visibility);
			$doc->save();
		}

		return self::getSuccessView();
	}
}