<?php
/**
 * website_LoadMarkersPropertiesAction
 * @package modules.website.actions
 */
class website_LoadMarkersPropertiesAction extends f_action_BaseJSONAction
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		$folder = $this->getDocumentInstanceFromRequest($request);
		return $this->sendJSON(array ('documents' => $folder->getMarkersInfos()));
	}
}