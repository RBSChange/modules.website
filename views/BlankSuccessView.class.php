<?php
/**
 * @package modules.website
 */
class website_BlankSuccessView extends change_View
{

	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	public function _execute($context, $request)
	{
		$this->setTemplateName('Blank', 'html');

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