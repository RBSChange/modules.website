<?php
/**
 * @package modules.website
 */
class website_BlankSuccessView extends f_view_BaseView
{

    /**
	 * @param Context $context
	 * @param Request $request
	 */
    public function _execute($context, $request)
    {
        $this->setTemplateName('Blank', K::HTML);

        if ($request->hasParameter('content'))
        {
            $this->setAttribute('content', $request->getParameter('content'));
        }

        $this->setAttribute('cssInclusion', file_get_contents($request->getAttribute('stylesheetPath')));
    }
    
	protected function sendHttpHeaders()
	{
	}
}