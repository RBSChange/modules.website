<?php
/**
 * website_SystemtopicScriptDocumentElement
 * @package modules.website.persistentdocument.import
 */
class website_SystemtopicScriptDocumentElement extends import_ScriptDocumentElement
{
    /**
     * @return website_persistentdocument_systemtopic
     */
    protected function initPersistentDocument()
    {
    	return website_SystemtopicService::getInstance()->getNewDocumentInstance();
    }
    
    /**
	 * @return f_persistentdocument_PersistentDocumentModel
	 */
	protected function getDocumentModel()
	{
		return f_persistentdocument_PersistentDocumentModel::getInstanceFromDocumentModelName('modules_website/systemtopic');
	}
}