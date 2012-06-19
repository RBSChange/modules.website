<?php
/**
 * website_PagereferenceScriptDocumentElement
 * @package modules.website.persistentdocument.import
 */
class website_PagereferenceScriptDocumentElement extends import_ScriptDocumentElement
{
	/**
	 * @return website_persistentdocument_pagereference
	 */
	protected function initPersistentDocument()
	{
		return website_PagereferenceService::getInstance()->getNewDocumentInstance();
	}
}