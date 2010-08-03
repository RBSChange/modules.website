<?php
/**
 * website_SystemtopicScriptDocumentElement
 * @package modules.website.persistentdocument.import
 */
class website_SystemtopicScriptDocumentElement extends website_TopicScriptDocumentElement
{
	/**
	 * @return website_persistentdocument_systemtopic
	 */
	protected function initPersistentDocument()
	{
		// Deprecated attribute documentid. Use byDocumentId generic attribute.
		if (isset($this->attributes['documentid']))
		{
			return DocumentHelper::getDocumentInstance($this->attributes['documentid'], 'modules_website/systemtopic');
		}
		return website_SystemtopicService::getInstance()->getNewDocumentInstance();
	}

	/**
	 * @return f_persistentdocument_PersistentDocumentModel
	 */
	protected function getDocumentModel()
	{
		return f_persistentdocument_PersistentDocumentModel::getInstanceFromDocumentModelName('modules_website/systemtopic');
	}
}