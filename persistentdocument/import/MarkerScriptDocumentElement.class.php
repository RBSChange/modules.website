<?php
/**
 * website_MarkerScriptDocumentElement
 * @package modules.website.persistentdocument.import
 */
class website_MarkerScriptDocumentElement extends import_ScriptDocumentElement
{
	/**
	 * @return website_persistentdocument_marker
	 */
	protected function initPersistentDocument()
	{
		return website_MarkerService::getInstance()->getNewDocumentInstance();
	}
	
	/**
	 * @return f_persistentdocument_PersistentDocumentModel
	 */
	protected function getDocumentModel()
	{
		return f_persistentdocument_PersistentDocumentModel::getInstanceFromDocumentModelName('modules_website/marker');
	}
}