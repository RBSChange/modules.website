<?php
class website_PagegroupPublicatedChooser extends change_BaseService
{
	/**
	 * @var website_PagegroupPublicatedChooser
	 */
	private static $instance;

	/**
	 * @return website_PagegroupPublicatedChooser
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * @param array<website_persistentdocument_pageversion> $elements
	 * @return website_persistentdocument_pageversion
	 */
	public function select($elements)
	{
		if (Framework::isDebugEnabled())
		{
			Framework::debug(__METHOD__ . ' versions count ' . count($elements));
		}
	    $selected = null;
	    $lastSelected = null;
	    if (count($elements) != 0)
	    {
    	    foreach ($elements as $element)
    	    {
    	    	if ($element->isContextLangAvailable())
    	    	{
					$lastSelected = $element;
					if ($element->isPublished())
					{
						if (is_null($selected))
						{
							$selected = $element;
						}
						else if ($selected->getStartpublicationdate() <= $element->getStartpublicationdate())
						{
							$selected = $element;
						}
					}
    	    	}
    	    }

    	    if (is_null($selected) && !is_null($lastSelected))
    	    {
    	    	$selected = $lastSelected;
    	    }
	    }

		if (Framework::isDebugEnabled() && $selected !== null)
		{
			Framework::debug(__METHOD__ . ' selection ' . $selected->__toString());
		}
	    return $selected;
	}
}
