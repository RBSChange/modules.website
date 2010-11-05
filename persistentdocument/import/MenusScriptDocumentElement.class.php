<?php
class website_MenusScriptDocumentElement extends import_ScriptDocumentElement
{
     /**
     * @return f_persistentdocument_PersistentDocument
     */
    protected function initPersistentDocument()
    {
        $parentWebsite = $this->getParentDocument();
        return website_WebsiteService::getInstance()->getMenuFolder($parentWebsite->getPersistentDocument());
    } 
    
    protected function getDocumentModel()
    {
    	return f_persistentdocument_PersistentDocumentModel::getInstanceFromDocumentModelName("modules_website/menufolder");
    }
    
    public function process()
    {
        // Init default menus folder.
        $this->getPersistentDocument();
        
        // Ignore parent process.
    }
}