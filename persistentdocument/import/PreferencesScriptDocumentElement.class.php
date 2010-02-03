<?php
/**
 * website_PreferencesScriptDocumentElement
 * @package modules.website.persistentdocument.import
 */
class website_PreferencesScriptDocumentElement extends import_ScriptDocumentElement
{
    /**
     * @return website_persistentdocument_preferences
     */
    protected function initPersistentDocument()
    {
    	$pref = ModuleService::getInstance()->getPreferencesDocument('website');
    	return ($pref !== null) ? $pref : website_PreferencesService::getInstance()->getNewDocumentInstance();
    }
}