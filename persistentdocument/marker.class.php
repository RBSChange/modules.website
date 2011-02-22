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
	 * @return websit_persistentdocument_website
	 */
	public function getWebsite()
	{
		return $this->getDocumentService()->getWebsiteByMarker($this);
	}
	
	/**
	 * @return String
	 */
	public function getMarkerType()
	{
		return substr($this->getPersistentModel()->getModuleName(), 6);
	}
	
	/**
	 * @return String
	 */
	public final function getSpecificPropertiesJSON()
	{
		return JsonService::getInstance()->encode($this->getSpecificProperties());
	}
	
	/**
	 * @param String $value
	 */
	public final function setSpecificPropertiesJSON($value)
	{
		 $this->setSpecificProperties(JsonService::getInstance()->decode($value));
	}
	
	/**
	 * @return Array<String, mixed>
	 */
	public function getSpecificProperties()
	{
		// This method should be overloaded in all marker final classes.
		throw new Exception(get_class($this).'::getSpecificProperties() is not defined!');
	}
	
	/**
	 * param Array<String, mixed> $value
	 */
	public function setSpecificProperties($value)
	{
		// This method should be overloaded in all marker final classes.
		throw new Exception(get_class($this).'::getSpecificProperties() is not defined!');
	}
}