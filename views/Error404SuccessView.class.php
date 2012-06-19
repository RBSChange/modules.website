<?php
class website_Error404SuccessView extends change_View
{
	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	public function _execute($context, $request)
	{
		$this->forceModuleName('website');
		$this->setTemplateName('Website-Error404-Success', 'html');
		$this->setAttribute('title', f_Locale::translate('&modules.website.frontoffice.Page-not-found;'));
		$this->setAttribute('requestedUrl', substr($request->getAttribute('requestedUrl'), 1)); // skip starting slash
		$this->setAttribute('lastVisitedUrl', $request->getAttribute('lastVisitedUrl'));
	}
}