<?php
class website_LinkedtopicScriptDocumentElement extends import_ScriptDocumentElement
{
    /**
     * @return website_persistentdocument_topic
     */
    protected function initPersistentDocument()
    {
        $refid = $this->attributes['refid'];
        return $this->script->getElementById($refid)->getPersistentDocument();
    }
    
    public function process()
    {
        $topic = $this->getPersistentDocument();
        $rootFolder =  $this->getParentDocument()->getPersistentDocument();
        $rootFolder->addTopics($topic);
        $rootFolder->save();
    }
    
	/**
	 * @return f_persistentdocument_PersistentDocumentModel
	 */
	protected function getDocumentModel()
	{
		return f_persistentdocument_PersistentDocumentModel::getInstanceFromDocumentModelName('modules_website/topic');
	}
}