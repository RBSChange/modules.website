<?php
/**
 * Class where to put your custom methods for document website_persistentdocument_markerfolder
 * @package website.persistentdocument
 */
class website_persistentdocument_markerfolder extends website_persistentdocument_markerfolderbase 
{
	/**
	 * @see f_persistentdocument_PersistentDocumentImpl::getTreeNodeLabel()
	 * @return String
	 */
	function getTreeNodeLabel()
	{
		return f_Locale::translateUI($this->getLabel());
	}
	
	/**
	 * @return string
	 */
	public function getMarkersJSON()
	{
		return JsonService::getInstance()->encode($this->getMarkersInfos());
	}
	
	/**
	 * @return Array
	 */
	public function getMarkersInfos()
	{
		return $this->getDocumentService()->getMarkersInfos($this);
	}
}