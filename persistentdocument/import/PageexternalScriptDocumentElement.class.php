<?php
/**
 * website_PageexternalScriptDocumentElement
 * @package modules.website.persistentdocument.import
 */
class website_PageexternalScriptDocumentElement extends import_ScriptDocumentElement
{
	/**
	 * @return website_persistentdocument_pageexternal
	 */
	protected function initPersistentDocument()
	{
		return website_PageexternalService::getInstance()->getNewDocumentInstance();
	}
}