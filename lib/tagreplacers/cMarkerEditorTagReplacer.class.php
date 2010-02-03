<?php
/**
 * @package modules.website
 */
class website_lib_cMarkerEditorTagReplacer extends f_util_TagReplacer
{
	protected function preRun()
	{
		$buttons = array();
		$icon = MediaHelper::getIcon('add', MediaHelper::SMALL);	
		foreach (website_MarkerService::getInstance()->getMarkerTypeList() as $markerType)
		{
			$buttons[] = '<xul:toolbarbutton action="add" markerType="'.$markerType.'" image="'.$icon.'" label="&amp;modules.marker'.$markerType.'.bo.general.Add-new-marker;" />';
		}		
		$this->setReplacement('MARKER_CREATION_BUTTONS', implode(K::CRLF, $buttons));
	}
}