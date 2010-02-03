<?php
class Breadcrumb extends NavigationElementImpl
{

	private $xhtmlSeparator = ' &gt; ';
	private $textSeparator = ' > ';


	/**
	 * Set the XHTML separator.
	 *
	 * @param string $separator
	 * @return Breadcrumb
	 */
	public function setHtmlSeparator($separator)
	{
	    $this->xhtmlSeparator = $separator;
	    return $this;
	}


	/**
	 * Set the TEXT separator.
	 *
	 * @param string $separator
	 * @return Breadcrumb
	 */
	public function setTextSeparator($separator)
	{
	    $this->textSeparator = $separator;
	    return $this;
	}


	public function renderAsXhtml()
	{
		$parts = array();
		foreach ($this as $entry)
		{
			$parts[] = $this->buildA($entry, '');
		}
		return join($this->xhtmlSeparator, $parts);
	}

	public function renderAsText()
	{
		$parts = array();
		foreach ($this as $entry)
		{
			$parts[] = $entry->getLabel();
		}
		return join($this->textSeparator, $parts);
	}

	public function renderAsJavascript()
	{
		$jsPagePath = array();
		$jsPagePath[] = sprintf(
			'"%s"',	addslashes(f_Locale::translate('&modules.website.frontoffice.thread.Homepage-href-name;'))
			);
		foreach ($this as $entry)
		{
			$jsPagePath[] = sprintf('"%s"', addslashes($entry->getLabel()));
		}
		return sprintf('[%s]', implode(',', $jsPagePath));
	}
}