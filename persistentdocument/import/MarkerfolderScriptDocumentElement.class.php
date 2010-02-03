<?php
/**
 * website_MarkerfolderScriptDocumentElement
 * @package modules.website.persistentdocument.import
 */
class website_MarkerfolderScriptDocumentElement extends import_ScriptDocumentElement
{
    /**
     * @return website_persistentdocument_markerfolder
     */
    protected function initPersistentDocument()
    {
    	return website_MarkerfolderService::getInstance()->getNewDocumentInstance();
    }
    
    /**
	 * @return f_persistentdocument_PersistentDocumentModel
	 */
	protected function getDocumentModel()
	{
		return f_persistentdocument_PersistentDocumentModel::getInstanceFromDocumentModelName('modules_website/markerfolder');
	}
}