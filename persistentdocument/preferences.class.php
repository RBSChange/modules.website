<?php
/**
 * @package website
 */
class website_persistentdocument_preferences extends website_persistentdocument_preferencesbase 
{
	/**
	 * Define the label of the tree node of the document.
	 * By default, this method returns the label property value.
	 * @return string
	 */
	public function getTreeNodeLabel()
	{
		return LocaleService::getInstance()->trans('m.website.bo.general.module-name', array('ucf'));
	}
}