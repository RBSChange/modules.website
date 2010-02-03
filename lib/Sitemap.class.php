<?php
class Sitemap extends NavigationElementImpl
{
	private $anchors = array();
	private $isBuilt = false;


	public function __construct()
	{
		$this->setMaxLevel(5);
	}


	/**
	 * Renders the sitemap as XHTML.
	 *
	 * @return string The generated XHTML code for this sitemap.
	 */
	public function renderAsXhtml()
	{
		if (!$this->isBuilt)
		{
			$this->buildXhtml();
		}
		return $this->xhtmlContents;
	}


	/**
	 * Returns the anchors to the first level entries.
	 *
	 * @return array<string>
	 */
	public function getAnchors()
	{
		if (!$this->isBuilt)
		{
			$this->buildXhtml();
		}
		return $this->anchors;
	}


	/**
	 * Renders the sitemap as a text string.
	 *
	 * @return string
	 */
	public function renderAsText()
	{
		$parts = array();
		foreach ($this as $entry)
		{
			$parts[] = str_repeat('. ', $entry->getLevel()) . $entry->getLabel();
		}
		return join($this->textCRLF, $parts);
	}


	///////////////////////////////////////////////////////////////////////////
	// Private methods                                                       //
	///////////////////////////////////////////////////////////////////////////


	/**
	 * PLEASE UPDATE THE CORRESPONDING TESTCASE IF YOU CHANGE SOMETHING HERE!
	 */
	private function buildXhtml()
	{
		$siteMapContent = array();
		$previousLevel = -1;

		foreach ($this as $entry)
		{
			$level = $entry->getLevel();

			if ( !$this->isRestricted($entry) && ($level <= $this->maxLevel || $this->maxLevel < 0) )
			{
				if ($level > $previousLevel)
				{
					$siteMapContent[] = $this->beginUL($entry);
				}
				else
				{
					if ($previousLevel >= 0)
					{
						$siteMapContent[] = $this->endLI();
					}
					if ($level < $previousLevel)
					{
				    	$siteMapContent[] = str_repeat($this->endUL().$this->xhtmlCRLF.$this->endLI(), $previousLevel - $level);
					}
				}

				$siteMapContent[] = $this->beginLI($entry);

				if ($level == 0)
				{
					$anchor = 'map_topic_'.$entry->getId();
					$this->anchors[] = array('name' => $anchor, 'label' => $entry->getLabel());
				}
				else
				{
					$anchor = '';
				}

				$siteMapContent[] = $this->buildA($entry, $anchor);
				$previousLevel = $level;
			}
		}

		if ($level > 0)
		{
			$siteMapContent[] = str_repeat($this->endUL().$this->xhtmlCRLF.$this->endLI(), $previousLevel - $level);
		}

		if ( ! empty($entry) )
		{
			$siteMapContent[] = $this->endLI();
			$siteMapContent[] = $this->endUL();
		}

		$this->xhtmlContents = join($this->xhtmlCRLF, $siteMapContent);
		$this->isBuilt = true;
	}
}