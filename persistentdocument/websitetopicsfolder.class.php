<?php
/**
 * Class where to put your custom methods for document website_persistentdocument_websitetopicsfolder
 * @package modules.website.persistentdocument
 */
class website_persistentdocument_websitetopicsfolder extends website_persistentdocument_websitetopicsfolderbase 
{
	/**
	 * @param string $moduleName
	 * @param string $treeType
	 * @param array<string, string> $nodeAttributes
	 */
	protected function addTreeAttributes($moduleName, $treeType, &$nodeAttributes)
	{
		if ($this->getWebsite() !== null)
		{
			$nodeAttributes['websiteId'] = $this->getWebsite()->getId();
		}
	}
	
	/**
	 * @param string $actionType
	 * @param array $formProperties
	 */
//	public function addFormProperties($propertiesNames, &$formProperties)
//	{	
//	}
}