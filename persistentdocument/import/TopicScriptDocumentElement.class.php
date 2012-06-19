<?php
class website_TopicScriptDocumentElement extends import_ScriptDocumentElement
{
	/**
	 * @return f_persistentdocument_PersistentDocument
	 */
	protected function initPersistentDocument()
	{
		// Deprecated attribute documentid. Use byDocumentId generic attribute.
		if (isset($this->attributes['documentid']))
		{
			return DocumentHelper::getDocumentInstance($this->attributes['documentid'], 'modules_website/topic');
		}
		return website_TopicService::getInstance()->getNewDocumentInstance();
	}
	
	/**
	 * @return Array<String, Mixed>
	 */
	protected function getDocumentProperties()
	{
		$properties = parent::getDocumentProperties();
		
		if (isset($properties['template']))
		{
			unset($properties['template']);
		}
		if (isset($properties['documentid']))
		{
			unset($properties['documentid']);
		}
		
		if (isset($properties['navigationVisibility']) && !is_numeric($properties['navigationVisibility']))
		{
			if ($properties['navigationVisibility'] == 'visible')
			{
				$properties['navigationVisibility'] = website_ModuleService::VISIBLE;
			}
			elseif ($properties['navigationVisibility'] == 'hidden')
			{
				$properties['navigationVisibility'] = website_ModuleService::HIDDEN;
			}
			else
			{
				$properties['navigationVisibility'] = website_ModuleService::HIDDEN_IN_MENU_ONLY;
			}
		}
		
		return $properties;
	}
}