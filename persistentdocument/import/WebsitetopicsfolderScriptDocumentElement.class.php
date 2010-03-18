<?php
/**
 * website_WebsitetopicsfolderScriptDocumentElement
 * @package modules.website.persistentdocument.import
 */
class website_WebsitetopicsfolderScriptDocumentElement extends import_ScriptDocumentElement
{
    /**
     * @return website_persistentdocument_websitetopicsfolder
     */
    protected function initPersistentDocument()
    {
    	return website_WebsitetopicsfolderService::getInstance()->getNewDocumentInstance();
    }
    
    /**
	 * @return f_persistentdocument_PersistentDocumentModel
	 */
	protected function getDocumentModel()
	{
		return f_persistentdocument_PersistentDocumentModel::getInstanceFromDocumentModelName('modules_website/websitetopicsfolder');
	}
}