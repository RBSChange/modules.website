<?php
class website_Error404SuccessView extends f_view_BaseView
{
    /**
	 * @param Context $context
	 * @param Request $request
	 */
    public function _execute($context, $request)
    {
        $this->forceModuleName('website');
    	$this->setTemplateName('Website-Error404-Success', K::HTML);
        $this->setAttribute('title', f_Locale::translate('&modules.website.frontoffice.Page-not-found;'));
        $this->setAttribute('requestedUrl', substr($request->getAttribute('requestedUrl'), 1)); // skip starting slash
        $this->setAttribute('lastVisitedUrl', $request->getAttribute('lastVisitedUrl'));
    }
}