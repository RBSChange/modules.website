<?php
class website_MenuitemfunctionScriptDocumentElement extends import_ScriptDocumentElement
{
    /**
     * @return f_persistentdocument_PersistentDocument
     */
    protected function initPersistentDocument()
    {
        return website_MenuitemfunctionService::getInstance()->getNewDocumentInstance();
    } 
    
    protected function getDocumentProperties ()
    {
        $properties = parent::getDocumentProperties();
        if (isset($properties['function']))
        {
            $properties['url'] = 'function:'.$properties['function'];
            unset($properties['function']);
        }
        return $properties;
    }
}