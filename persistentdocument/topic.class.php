<?php
/**
 * website_persistentdocument_topic
 * @package website
 */
class website_persistentdocument_topic extends website_persistentdocument_topicbase implements website_PublishableElement
{
	/**
	 * @return integer or null
	 */
	public function getIndexPageId()
	{
		$indexPage = $this->getIndexPage();
		if ( ! is_null($indexPage) )
		{
			return $indexPage->getId();
		}
		return null;
	}
	
	/**
	 * @return boolean
	 */
	public function hasPublishedIndexPage()
	{
		return $this->getIndexPage() && $this->getIndexPage()->isPublished();
	}
	
	/**
	 * @param string $moduleName
	 * @param string $treeType
	 * @param array<string, string> $nodeAttributes
	 */	
	protected function addTreeAttributes($moduleName, $treeType, &$nodeAttributes)
	{
	    $blocClass = $moduleName .'_BlockTopicAction';
	    if (f_util_ClassUtils::classExists($blocClass))
	    {
	        $nodeAttributes[f_tree_parser_AttributesBuilder::BLOCK_ATTRIBUTE] = 'modules_' . $moduleName . '_topic';
	    }
	    else
	    {
	        $nodeAttributes[f_tree_parser_AttributesBuilder::BLOCK_ATTRIBUTE] = '';
	    }
		if ($treeType == 'wlist')
		{
	    	$nodeAttributes['thumbnailsrc'] = MediaHelper::getIcon('topic');
		}
	}
	
	/**
	 * @return string
	 */
	public function getPathOf()
	{
		return $this->getDocumentService()->getPathOf($this);
	}
	
	/**
	 * @see website_PublishableElement::getNavigationtitle()
	 * @return string
	 */
	public function getNavigationtitle()
	{
		return $this->getLabel();
	}
	
	/**
	 * @return string
	 */
	public function getNavigationtitleAsHtml()
	{
		return $this->getLabelAsHtml();
	}
	
	/**
	 * @see website_PublishableElement::getNavigationURL()
	 * @return string
	 */
	public function getNavigationURL()
	{
		if ($this->getIndexPage() !== null)
		{
			return LinkHelper::getDocumentUrl($this);
		}
		return null;
	}
}