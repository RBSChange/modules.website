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
		$this->setAttribute('title', LocaleService::getInstance()->trans('m.website.frontoffice.page-not-found', array('ucf')));
		$this->setAttribute('requestedUrl', substr($request->getAttribute('requestedUrl'), 1)); // skip starting slash
		$this->setAttribute('lastVisitedUrl', $request->getAttribute('lastVisitedUrl'));
	}
}