<?php
class website_persistentdocument_pagereference extends website_persistentdocument_pagereferencebase 
{
    
     /**
	 * @param string $moduleName
	 * @param string $treeType
	 * @param array<string, string> $nodeAttributes
	 */	
	protected function addTreeAttributes($moduleName, $treeType, &$nodeAttributes)
	{
	    parent::addTreeAttributes($moduleName, $treeType, $nodeAttributes);
	    $label = $this->isContextLangAvailable() ? $this->getLabel() : $this->getVoLabel();
	    $path = $this->getDocumentService()->getPathOf(DocumentHelper::getDocumentInstance($this->getReferenceofid()));
	    $nodeAttributes['label'] = $path . ' > ' . $label;
	}	
}
