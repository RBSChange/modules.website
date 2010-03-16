<?php
/**
 * website_MarkerfolderService
 * @package website
 */
class website_MarkerfolderService extends generic_FolderService
{
	/**
	 * @var website_MarkerfolderService
	 */
	private static $instance;

	/**
	 * @return website_MarkerfolderService
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = self::getServiceClassInstance(get_class());
		}
		return self::$instance;
	}

	/**
	 * @return website_persistentdocument_markerfolder
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_website/markerfolder');
	}

	/**
	 * Create a query based on 'modules_website/markerfolder' model.
	 * Return document that are instance of modules_website/markerfolder,
	 * including potential children.
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->pp->createQuery('modules_website/markerfolder');
	}
	
	/**
	 * Create a query based on 'modules_website/markerfolder' model.
	 * Only documents that are strictly instance of modules_website/markerfolder
	 * (not children) will be retrieved
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createStrictQuery()
	{
		return $this->pp->createQuery('modules_website/markerfolder', false);
	}
	
	/**
	 * @see f_persistentdocument_DocumentService::preDelete()
	 *
	 * @param website_persistentdocument_markerfolder $document
	 */
	protected function preDelete($document)
	{
		// DELETE All Marker inner folder.
		website_MarkerService::getInstance()->createQuery()->delete();
	}

	/**
	 * @see f_persistentdocument_DocumentService::onMoveToStart()
	 * @param website_persistentdocument_markerfolder $document
	 * @param Integer $destId
	 */
	protected function onMoveToStart($document, $destId)
	{
		if ($this->getParentOf($document)->getId() !== $destId)
		{
			throw new BaseException('Can\'t move a markerfolder from a website to another!', 'modules.website.errors.Cant-move-markerfolder-to-other-website');
		}
	}
	
	/**
	 * @param website_persistentdocument_markerfolder $folder
	 * @return Array
	 */
	public function getMarkersInfos($folder)
	{
		$infos = array();
		foreach ($this->getChildrenOf($folder) as $marker)
		{
			$model = $marker->getPersistentModel();
			$infos[] = array(
				'id' => $marker->getId(),
				'isPublished' => $marker->isPublished(),
				'isNotPublished' => !$marker->isPublished(),
				'model' => $model->getName(),
				'label' => $marker->getLabel(),
				'type' => f_Locale::translate('&modules.' . $model->getModuleName() . '.bo.general.MarkerType;'),
				'langs' => str_replace(',', ', ', $marker->getLangs())
			);
		}
		return $infos;
	}
	
	/**
	 * @param f_persistentdocument_PersistentDocument $document
	 * @param string $forModuleName
	 * @return array
	 */
	public function getResume($document, $forModuleName, $allowedSections = null)
	{
		$data = parent::getResume($document, $forModuleName, $allowedSections);
		
		$data['properties']['label'] = $document->getTreeNodeLabel();
		
		return $data;
	}
}