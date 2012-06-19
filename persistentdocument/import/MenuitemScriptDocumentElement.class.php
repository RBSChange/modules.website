<?php
/**
 * website_MenuitemScriptDocumentElement
 * @package modules.website.persistentdocument.import
 */
class website_MenuitemScriptDocumentElement extends import_ScriptDocumentElement
{
	/**
	 * @return website_persistentdocument_menuitem
	 */
	protected function initPersistentDocument()
	{
		return website_MenuitemService::getInstance()->getNewDocumentInstance();
	}
}