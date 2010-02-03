<?php
/**
 * website_MenufolderScriptDocumentElement
 * @package modules.website.persistentdocument.import
 */
class website_MenufolderScriptDocumentElement extends import_ScriptDocumentElement
{
    /**
     * @return website_persistentdocument_menufolder
     */
    protected function initPersistentDocument()
    {
    	return website_MenufolderService::getInstance()->getNewDocumentInstance();
    }
}