<?php
/**
 * website_persistentdocument_pagegroup
 * @package website
 */
class website_persistentdocument_pagegroup extends website_persistentdocument_pagegroupbase 
{
	/**
	 * @see website_persistentdocument_pagebase::getBackofficeIndexedDocument()
	 * @return indexer_IndexedDocument
	 */
	public function getBackofficeIndexedDocument()
	{
		return null;
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
			$info['current'] = $info['id'] == $currentversionId;
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