<?php
class website_persistentdocument_menufolder extends website_persistentdocument_menufolderbase 
{
	
	/**
	 * @see f_persistentdocument_PersistentDocumentImpl::getTreeNodeLabel()
	 *
	 * @return String
	 */
	function getTreeNodeLabel()
	{
		return f_Locale::translateUI($this->getLabel());
	}

}