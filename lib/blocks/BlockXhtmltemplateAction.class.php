<?php
/**
 * website_BlockXhtmltemplateAction
 * @package modules.website.lib.blocks
 */
class website_BlockXhtmltemplateAction extends website_BlockAction
{
	/**
	 * @see website_BlockAction::execute()
	 *
	 * @param f_mvc_Request $request
	 * @param f_mvc_Response $response
	 * @return String
	 */
	public function execute($request, $response)
	{
		$templateName = $this->getConfiguration()->getTemplate();
		if (f_util_StringUtils::isEmpty($templateName))
		{
			return website_BlockView::NONE;
		}
		$request->setAttribute('page', $this->getPage());
		$tpl = null;
		if ($this->isInBackoffice())
		{
			$tpl = $this->getTemplate(ucfirst($templateName . website_BlockView::BACKOFFICE));
		}
		return $tpl ? $tpl : ucfirst($templateName);
	}
}