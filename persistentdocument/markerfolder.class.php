<?php
/**
 * @deprecated
 */
class website_persistentdocument_markerfolder extends website_persistentdocument_markerfolderbase 
{
	/**
	 * @deprecated
	 */
	public function getMarkersJSON()
	{
		return JsonService::getInstance()->encode($this->getMarkersInfos());
	}
	
	/**
	 * @deprecated
	 */
	public function getMarkersInfos()
	{
		return $this->getDocumentService()->getMarkersInfos($this);
	}
}