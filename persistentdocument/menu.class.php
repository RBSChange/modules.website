<?php
/**
 * website_persistentdocument_menu
 * @package website
 */
class website_persistentdocument_menu extends website_persistentdocument_menubase
{
	/**
	 * Adds a menuitem to the menu.
	 * If the given $newValue is not a website_persistentdocument_menuitem,
	 * then a new website_persistentdocument_menuitem is created
	 * poiting to the given document. This menuitem is then appended to the menu.
	 *
	 * @param website_persistentdocument_menuitemdocument $newValue  Can't not be null
	 * @return void
	 */
	public function addMenuItem($newValue)
	{
		if ($newValue instanceof website_persistentdocument_menuitem)
		{
			parent::addMenuItem($newValue);
		}
		else
		{
			throw new Exception('Invalid document type');
		}
	}


	
	
    /**
	 * @param string $moduleName
	 * @param string $treeType
	 * @param array<string, string> $nodeAttributes
	 */	
	protected function addTreeAttributes($moduleName, $treeType, &$nodeAttributes)
	{
	    if ($treeType == 'wlist')
	    {
            $ts = TagService::getInstance();
            $tagObjectArray = $ts->getTagObjects($this);
            $label = array();
            foreach ($tagObjectArray as $tagObject)
            {
                if ($ts->isContextualTag($tagObject->getValue()))
                {
                    $label[] = f_Locale::translateUI($tagObject->getLabel());
                }
            }
            if (f_util_ArrayUtils::isEmpty($label))
            {
            	$label[] = f_Locale::translateUI('&modules.website.bo.general.no-tag-available;');
            }
            $nodeAttributes['tagLabel'] = join(', ', $label);   
	    }
	    else
	    {
	        $nodeAttributes[tree_parser_XmlTreeParser::SKIP_CHILDREN] = true;  
	    }
	}	
	
}