<?php
/**
 * website_patch_0309
 * @package modules.website
 */
class website_patch_0309 extends patch_BasePatch
{
	/**
	 * Entry point of the patch execution.
	 */
	public function execute()
	{
		parent::execute();
		
		$pp = f_persistentdocument_PersistentProvider::getInstance();
		$mfs = website_MarkerfolderService::getInstance();
		$ms = website_MarkerService::getInstance();
		$ts = TreeService::getInstance();
		
		// Generate the marker folders.
		foreach (website_WebsiteService::getInstance()->getAll() as $website)
		{
			$markerFolder = $mfs->getNewDocumentInstance();
			$markerFolder->setLabel('&modules.website.bo.general.Marker-folder-label;');
			$markerFolder->save($website->getId());
		}
		
		// Rename the marker table and add the new field.
		$this->executeSQLQuery("RENAME TABLE m_basemarker_doc_marker TO m_website_doc_marker;");
		
		// Add the new lang field.
		$newPath = f_util_FileUtils::buildWebeditPath('modules/website/persistentdocument/marker.xml');
		$newModel = generator_PersistentModel::loadModelFromString(f_util_FileUtils::read($newPath), 'website', 'marker');
		$newProp = $newModel->getPropertyByName('langs');
		f_persistentdocument_PersistentProvider::getInstance()->addProperty('website', 'marker', $newProp);
		
		// Import new list.
		$this->executeLocalXmlScript('list.xml');
		
		// Migrate marker relations.
		$statement = $pp->executeSQLSelect('SELECT website, marker, document_lang FROM `m_basemarker_doc_markerrelation`');
		$statement->execute();
		$result = $statement->fetchAll();
		foreach ($result as $row)
		{
			$marker = DocumentHelper::getDocumentInstance($row['marker']);
			
			// Move the marker as a child of the markerfolder of its website.
			if (!($ms->getParentOf($marker) instanceof website_persistentdocument_markerfolder))
			{
				$folder = f_util_ArrayUtils::firstElement($mfs->getByParentId($row['website']));
				$oldNode = $ts->getInstanceByDocument($marker);
				$parentNode = $ts->getInstanceByDocument($folder);
				if ($oldNode !== null)
				{
					$ts->deleteNode($oldNode);
				}
				$ts->newLastChildForNode($parentNode, $marker->getId());
			}
			
			// Set langs.
			$lang = $row['document_lang'];
			$langs = $marker->getLangsArray();
			if (!in_array($lang, $langs))
			{
				$langs[] = $lang;
				$marker->setLangsArray($langs);
				$marker->save();
			}
		}
		
		$this->executeSQLQuery("DROP TABLE m_basemarker_doc_markerrelation;");
		$this->executeSQLQuery("DELETE FROM f_relation WHERE document_model_id1 = 'modules_basemarker_markerrelation';");
		$this->executeSQLQuery("DELETE FROM f_relation WHERE document_model_id2 = 'modules_basemarker_markerrelation';");
		$this->executeSQLQuery("DELETE FROM f_document WHERE document_model = 'modules_basemarker_markerrelation';");
	}

	/**
	 * Returns the name of the module the patch belongs to.
	 *
	 * @return String
	 */
	protected final function getModuleName()
	{
		return 'website';
	}

	/**
	 * Returns the number of the current patch.
	 * @return String
	 */
	protected final function getNumber()
	{
		return '0309';
	}
}