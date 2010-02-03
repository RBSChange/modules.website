<?php
/**
 * website_TemplateScriptDocumentElement
 * @package modules.website.persistentdocument.import
 */
class website_TemplateScriptDocumentElement extends import_ScriptDocumentElement
{
    /**
     * @return website_persistentdocument_template
     */
    protected function initPersistentDocument()
    {
    	return website_TemplateService::getInstance()->getNewDocumentInstance();
    }
}