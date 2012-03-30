<?php
/**
 * @deprecated
 */
class website_MarkerfolderScriptDocumentElement extends import_ScriptDocumentElement
{
    /**
     * @deprecated
     */
    protected function initPersistentDocument()
    {
    	return website_MarkerfolderService::getInstance()->getNewDocumentInstance();
    }
    
    /**
	 * @deprecated
	 */
	protected function getDocumentModel()
	{
		return f_persistentdocument_PersistentDocumentModel::getInstanceFromDocumentModelName('modules_website/markerfolder');
	}
}