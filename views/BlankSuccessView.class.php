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

        $this->setAttribute('cssInclusion', $request->getAttribute('cssInclusion'));
        $this->setAttribute('cssPageInclusion', $request->getAttribute('cssPageInclusion'));
    }
    
	protected function sendHttpHeaders()
	{
	}
}