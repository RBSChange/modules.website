<?php
/**
 * Class where to put your custom methods for document website_persistentdocument_marker
 * @package website.persistentdocument
 */
class website_persistentdocument_marker extends website_persistentdocument_markerbase
{	
	/**
	 * @return String[]
	 */
	public function getLangsArray()
	{
		return explode(',', $this->getLangs());
	}
	
	/**
	 * @param String[] $array
	 */
	public function setLangsArray($array)
	{
		$this->setLangs(implode(',', $array));
	}
		
	/**
	 * @return String
	 */
	public function getMarkerType()
	{
		return substr($this->getPersistentModel()->getModuleName(), 6);
	}
}