<?php
/**
 * website_LoadMarkersPropertiesAction
 * @package modules.website.actions
 */
class website_LoadMarkersPropertiesAction extends change_JSONAction
{
	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	public function _execute($context, $request)
	{
		$folder = $this->getDocumentInstanceFromRequest($request);
		return $this->sendJSON(array ('documents' => $folder->getMarkersInfos()));
	}
}