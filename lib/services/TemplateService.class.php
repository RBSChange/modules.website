<?php
/**
 * @package modules.website
 * @method website_TemplateService getInstance()
 */
class website_TemplateService extends f_persistentdocument_DocumentService
{
	/**
	 * @return website_persistentdocument_template
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_website/template');
	}
	
	/**
	 * Create a query based on 'modules_modules_website/template' model
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->getPersistentProvider()->createQuery('modules_website/template');
	}
}