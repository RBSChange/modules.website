<?php
class website_MenuScriptDocumentElement extends import_ScriptDocumentElement
{
	/**
	 * @return f_persistentdocument_PersistentDocument
	 */
	protected function initPersistentDocument()
	{
		// Deprecated attribute tagname. Use byTag generic attribute.
		if (isset($this->attributes['tagname']))
		{
			$documents = TagService::getInstance()->getDocumentsByTag($this->attributes['tagname']);
			if (isset($documents[0]))
			{
				return $documents[0];
			}
		}
		return website_MenuService::getInstance()->getNewDocumentInstance();
	}
	
	protected function getDocumentProperties()
	{
		$properties = parent::getDocumentProperties();
		unset($properties['tagname']);
		return $properties;
	}
}