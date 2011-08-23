<?php
class website_MenufolderService extends f_persistentdocument_DocumentService
{
	/**
	 * @var website_MenufolderService
	 */
	private static $instance;

	/**
	 * @return website_MenufolderService
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
	 * @return website_persistentdocument_menufolder
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_website/menufolder');
	}

	/**
	 * Create a query based on 'modules_website/menufolder' model
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->pp->createQuery('modules_website/menufolder');
	}
	
	/**
	 * @see f_persistentdocument_DocumentService::onMoveToStart()
	 * @param website_persistentdocument_menufolder $document
	 * @param Integer $destId
	 */
	protected function onMoveToStart($document, $destId)
	{
		if ($this->getParentOf($document)->getId() !== $destId)
		{
			throw new BaseException('Can\'t move a menufolder from a website to another!', 'modules.website.errors.Cant-move-menufolder-to-other-website');
		}
	}
}