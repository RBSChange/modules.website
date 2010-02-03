<?php
/**
 * website_persistentdocument_menuitemfunction
 * @package website
 */
class website_persistentdocument_menuitemfunction
	extends website_persistentdocument_menuitemfunctionbase
{
	/**
	 * Returns the title used in the navigation elements on the website.
	 *
	 * @return string
	 */
	public function getNavigationtitle()
	{
		return $this->getLabel();
	}

    /**
	 * @param string $moduleName
	 * @param string $treeType
	 * @param array<string, string> $nodeAttributes
	 */	
	protected function addTreeAttributes($moduleName, $treeType, &$nodeAttributes)
	{
         $nodeAttributes['refers-to'] = $this->getUrl();
         $nodeAttributes['popup'] = f_Locale::translateUI('&modules.generic.backoffice.No;');	        
	}
}