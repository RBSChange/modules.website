<?php
class website_Breadcrumb
{
	private $elements = array();
	
	/**
	 * @param $navigationtitle
	 * @param $href
	 * @param $imageSrc
	 * @return website_BreadcrumbElement
	 */
	function addElement($navigationtitle, $href = null, $imageSrc = null)
	{
		$elem = new website_BreadcrumbElement();
		$elem->navigationtitle = $navigationtitle;
		$elem->href = $href;
		$elem->imageSrc = $imageSrc;
		
		$this->elements[] = $elem;
		
		return $elem;
	}
	
	/**
	 * @return Integer
	 */
	function getSize()
	{
		return count($this->elements);
	}
	
	/**
	 * @return website_BreadcrumbElement
	 */
	function getFirstElement()
	{
		return $this->elements[0];
	}
	
	/**
	 * @return website_BreadcrumbElement
	 */
	function getLastElement()
	{
		return f_util_ArrayUtils::lastElement($this->elements);
	}
	
	/**
	 * @return website_BreadcrumbElement[]
	 */
	function getElements()
	{
		$elementsCount = count($this->elements);
		if ($elementsCount == 1)
		{
			$this->elements[0]->class = "first last";
		}
		elseif ($elementsCount > 0)
		{
			$this->elements[0]->class = "first";
			$this->elements[$elementsCount-1]->class = "last";
		}
		return $this->elements;
	}
}

class website_BreadcrumbElement
{
	public $onlyImage = false;	
	public $href, $navigationtitle, $class, $imageSrc;
	
	/**
	 * @return website_BreadcrumbElement
	 */
	function setHref($href)
	{
		$this->href = $href;
		return $this;
	}
	
	/**
	 * @return website_BreadcrumbElement
	 */
	function setNavigationtitle($navigationtitle)
	{
		$this->navigationtitle = $navigationtitle;
		return $this;
	}
	
	/**
	 * @return string
	 */
	function getNavigationtitle()
	{
		return $this->navigationtitle;
	}
	
	/**
	 * @return string
	 */
	function getNavigationtitleAsHtml()
	{
		return f_util_HtmlUtils::textToHtml($this->navigationtitle);
	}
	
	/**
	 * @return website_BreadcrumbElement
	 */
	function setImageSrc($imageSrc)
	{
		$this->imageSrc = $imageSrc;
		return $this;
	}
	
	/**
	 * @return website_BreadcrumbElement
	 */
	function setOnlyImage()
	{
		$this->onlyImage = true;
		return $this;
	}
}