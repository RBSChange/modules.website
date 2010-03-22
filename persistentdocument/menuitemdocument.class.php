<?php
/**
 * website_persistentdocument_menuitemdocument
 * @package modules.website
 */
class website_persistentdocument_menuitemdocument extends website_persistentdocument_menuitemdocumentbase
{
	/**
	 * Returns the title used in the navigation elements on the website.
	 *
	 * @return string
	 */
	public function getNavigationtitle()
	{
		return website_WebsiteModuleService::getNavigationTitleFor($this->getDocument());
	}
	
	/**
	 * @see WebsiteHelper, WebsiteConstants
	 */
	public function getNavigationVisibility()
	{
		return $this->getDocument()->getNavigationVisibility();
	}
		
	/**
	 * @return array The popup parameters as an array.
	 */
	public function getPopupParametersArray()
	{
		$popupParameters = array();
		$paramArray = explode(',', $this->getPopupParameters());
		foreach ($paramArray as $p)
		{
			list ($n, $v) = explode(':', $p);
			$popupParameters[trim($n)] = trim($v);
		}
		return $popupParameters;
	}
	
	/**
	 * @param string $moduleName
	 * @param string $treeType
	 * @param array<string, string> $nodeAttributes
	 */
	protected function addTreeAttributes($moduleName, $treeType, &$nodeAttributes)
	{
		try 
        {
			$breadcrumb = website_WebsiteModuleService::getInstance()->getBreadcrumb($this->getDocument());
			$nodeAttributes['refers-to'] = $breadcrumb->renderAsText();
        }
        catch (Exception $e)
        {
        	$nodeAttributes['refers-to'] = 'ERROR: '.$e->getMessage();
        }
		if ($this->getPopup())
		{
			$nodeAttributes['popup'] = f_Locale::translateUI('&modules.generic.backoffice.Yes;');
			$params = $this->getPopupParametersArray();
			if ($params['width'] && $params['height'])
			{
				$nodeAttributes['popup'] .= ' (' . $params['width'] . ' x ' . $params['height'] . ')';
			}
		}
		else
		{
			$nodeAttributes['popup'] = f_Locale::translateUI('&modules.generic.backoffice.No;');
		}
		// This tree attribute is used by wBaseModule to prevent a document from being translated
		$nodeAttributes['isTranslatable'] = "false";
	}
	
	/**
	 * @return String
	 */
	public function getPublicationstatus()
	{
		$doc = $this->getDocument();
		if ($doc !== null)
		{
			return $doc->getPublicationstatus();
		}
		return parent::getPublicationstatus();
	}
}