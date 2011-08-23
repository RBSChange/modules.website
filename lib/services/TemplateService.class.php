<?php
class website_TemplateService extends f_persistentdocument_DocumentService
{
	/**
	 * @var website_TemplateService
	 */
	private static $instance;
	
	/**
	 * @return website_TemplateService
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = new self();
		}
		return self::$instance;
	}
	
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
		return $this->pp->createQuery('modules_website/template');
	}
}