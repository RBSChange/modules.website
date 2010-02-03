<?php
/**
 * website_persistentdocument_pageversion
 * @package website
 */
class website_persistentdocument_pageversion extends website_persistentdocument_pageversionbase 
{
    /**
	 * @param string $moduleName
	 * @param string $treeType
	 * @param array<string, string> $nodeAttributes
	 */	
	protected function addTreeAttributes($moduleName, $treeType, &$nodeAttributes)
	{
	    parent::addTreeAttributes($moduleName, $treeType, $nodeAttributes);
	    $nodeAttributes['inGroup'] = 'inGroup';
        $versionOfPage = DocumentHelper::getDocumentInstance($this->getVersionofid());
        if ($this->getId() != $versionOfPage->getCurrentversionid())
        {
            $nodeAttributes[tree_parser_XmlTreeParser::ATTRIBUTE_PUBLICATED] = 0;
        }	        
	}
	
	/**
	 * @return array
	 */
	public function getInfoForPageGroup()
	{
		$data  = array('id' => $this->getId(), 
					'label' => $this->getLabel(),
					'startpublicationdate' => $this->getStartpublicationdate(),
					'endpublicationdate' => $this->getEndpublicationdate(),
					'publicationstatus' => $this->getPublicationstatus(),
					'status' => f_Locale::translateUI(DocumentHelper::getPublicationstatusLocaleKey($this)),
		);
		return $data;
	}
}