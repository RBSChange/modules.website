<?php
class website_MenuitemtextScriptDocumentElement extends import_ScriptDocumentElement
{
    /**
     * @return website_persistentdocument_menuitemtext
     */
    protected function initPersistentDocument()
    {
    	return website_MenuitemtextService::getInstance()->getNewDocumentInstance();
    }
    
    /**
     * @return import_ScriptDocumentElement
     */
    protected function getParentDocument()
    {
        return null;
    }
}