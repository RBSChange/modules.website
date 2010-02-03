<?php
/**
 * website_persistentdocument_preferences
 * @package website
 */
class website_persistentdocument_preferences extends website_persistentdocument_preferencesbase 
{
	/**
	 * @see f_persistentdocument_PersistentDocumentImpl::getLabel()
	 *
	 * @return String
	 */
	public function getLabel()
	{
		return f_Locale::translateUI(parent::getLabel());
	}	
}