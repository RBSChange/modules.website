<?php
/**
 * website_RewriteruleScriptDocumentElement
 * @package modules.website.persistentdocument.import
 */
class website_RewriteruleScriptDocumentElement extends import_ScriptDocumentElement
{
    /**
     * @return website_persistentdocument_rewriterule
     */
    protected function initPersistentDocument()
    {
    	return website_RewriteruleService::getInstance()->getNewDocumentInstance();
    }
    
    /**
	 * @return f_persistentdocument_PersistentDocumentModel
	 */
	protected function getDocumentModel()
	{
		return f_persistentdocument_PersistentDocumentModel::getInstanceFromDocumentModelName('modules_website/rewriterule');
	}
}