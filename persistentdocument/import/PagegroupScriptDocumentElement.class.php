<?php
/**
 * website_PagegroupScriptDocumentElement
 * @package modules.website.persistentdocument.import
 */
class website_PagegroupScriptDocumentElement extends import_ScriptDocumentElement
{
    /**
     * @return website_persistentdocument_pagegroup
     */
    protected function initPersistentDocument()
    {
    	return website_PagegroupService::getInstance()->getNewDocumentInstance();
    }
    
    /**
	 * @return f_persistentdocument_PersistentDocumentModel
	 */
	protected function getDocumentModel()
	{
		return f_persistentdocument_PersistentDocumentModel::getInstanceFromDocumentModelName('modules_website/pagegroup');
	}
}