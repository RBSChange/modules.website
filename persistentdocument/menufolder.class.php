<?php
class website_persistentdocument_menufolder extends website_persistentdocument_menufolderbase 
{
	/**
	 * @return String
	 */
	function getTreeNodeLabel()
	{
		return f_Locale::translateUI($this->getLabel());
	}
}