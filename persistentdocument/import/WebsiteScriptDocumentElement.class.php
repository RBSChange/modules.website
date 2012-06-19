<?php
class website_WebsiteScriptDocumentElement extends import_ScriptDocumentElement
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
		// Deprecated attribute documentid. Use byDocumentId generic attribute.
		if (isset($this->attributes['documentid']))
		{
			return DocumentHelper::getDocumentInstance($this->attributes['documentid']);
		}
		$website = website_WebsiteService::getInstance()->getNewDocumentInstance();
		$website->setDomain(Framework::getUIDefaultHost());
		return $website;
	}
	
	protected function getDocumentProperties()
	{
		$properties = parent::getDocumentProperties();
		if (isset($properties['tagname']))
		{
			unset($properties['tagname']);
		}
		if (isset($properties['template']))
		{
			unset($properties['template']);
		}
		
		if (isset($properties['documentid']))
		{
			unset($properties['documentid']);
		}
		return $properties;
	}
}