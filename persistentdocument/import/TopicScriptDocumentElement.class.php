<?php
class website_TopicScriptDocumentElement extends import_ScriptDocumentElement
{
    /**
     * @return f_persistentdocument_PersistentDocument
     */
    protected function initPersistentDocument()
    {
        return website_TopicService::getInstance()->getNewDocumentInstance();
    }

    /**
     * @return Array<String, Mixed>
     */
    protected function getDocumentProperties ()
    {
        $properties = parent::getDocumentProperties();

        if (isset($properties['template']))
        {
            unset($properties['template']);
        }

       	if (isset($properties['navigationVisibility']) && !is_numeric($properties['navigationVisibility']))
        {
            if ($properties['navigationVisibility'] == 'visible')
	        {
	            $properties['navigationVisibility'] = WebsiteConstants::VISIBILITY_VISIBLE;
	        }
	        elseif ($properties['navigationVisibility'] == 'hidden')
	        {
	            $properties['navigationVisibility'] = WebsiteConstants::VISIBILITY_HIDDEN;
	        }
	        else
	        {
	            $properties['navigationVisibility'] = WebsiteConstants::VISIBILITY_HIDDEN_IN_MENU_ONLY;
	        }
        }

        return $properties;
    }
    
    /**
     * @return void
     */
	public function endProcess()
	{
		$document = $this->getPersistentDocument();		
		foreach ($this->script->getChildren($this) as $child)
		{
			if ($child instanceof users_PermissionsScriptDocumentElement)
			{
				$child->setPermissions($document);
			}
		}
	}
}