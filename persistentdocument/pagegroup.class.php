<?php
/**
 * website_persistentdocument_pagegroup
 * @package website
 */
class website_persistentdocument_pagegroup extends website_persistentdocument_pagegroupbase 
{
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
			$info['currentlabel'] = ($info['current']) ? LocaleService::getInstance()->trans('f.boolean.true', array('ucf')) : '';
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