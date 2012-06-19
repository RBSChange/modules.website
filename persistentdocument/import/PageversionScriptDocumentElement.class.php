<?php
/**
 * website_PageversionScriptDocumentElement
 * @package modules.website.persistentdocument.import
 */
class website_PageversionScriptDocumentElement extends import_ScriptDocumentElement
{
	/**
	 * @return website_persistentdocument_pageversion
	 */
	protected function initPersistentDocument()
	{
		return website_PageversionService::getInstance()->getNewDocumentInstance();
	}
}