<?php
/**
 * website_persistentdocument_pagegroup
 * @package website
 */
class website_persistentdocument_pagegroup extends website_persistentdocument_pagegroupbase 
{

	/**
	 * @see website_persistentdocument_pagebase::getBackofficeIndexedDocument()
	 *
	 * @return indexer_IndexedDocument
	 */
	public function getBackofficeIndexedDocument()
	{
		return null;
	}
	
	/**
	 * @see website_persistentdocument_page::addTreeAttributes()
	 *
	 * @param string $moduleName
	 * @param string $treeType
	 * @param unknown_type $nodeAttributes
	 */
	protected function addTreeAttributes($moduleName, $treeType, &$nodeAttributes)
	{
		parent::addTreeAttributes($moduleName, $treeType, $nodeAttributes);
		
		$nodeAttributes[tree_parser_XmlTreeParser::FOLLOW_CHILDREN] = true;
		$currentVersionId = intval($this->getCurrentversionid());
		if ($currentVersionId != 0)
		{
			$nodeAttributes['related-id'] = $currentVersionId;
			$nodeAttributes['related-type'] = 'modules_website_pageversion';
		}
		else
		{
			$nodeAttributes['related-id'] = - 1;
		}
	}

	/**
	 * @return array
	 */
	public function getVersionsInfo()
	{
		$data = array();
		$versions = $this->getChildrenVersions();
		$currentversionId  = $this->getCurrentversionid();
		foreach ($versions as $version) 
		{
			$info = $version->getInfoForPageGroup();
			$info['current'] = $version->getId() == $currentversionId;
			
			if ($info['current'])
			{
				$info['currentlabel'] = f_Locale::translateUI('&framework.boolean.True;');
			}
			else
			{
				$info['currentlabel'] = '';
			}
			$data[] = $info;	
		}
		return array('versions' => $data);
	}
	
	/**
	 * @return string
	 */
	public function getVersionsJSON()
	{
		$datas = $this->getVersionsInfo();
		return JsonService::getInstance()->encode($datas['versions']);
	}
}
