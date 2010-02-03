<?php
/**
 * website_MenuitemdocumentScriptDocumentElement
 * @package modules.website.persistentdocument.import
 */
class website_MenuitemdocumentScriptDocumentElement extends import_ScriptDocumentElement
{
    /**
     * @return website_persistentdocument_menuitemdocument
     */
    protected function initPersistentDocument()
    {
    	return website_MenuitemdocumentService::getInstance()->getNewDocumentInstance();
    }
}