<?php
class website_MenuEntry
{	
	/**
	 * @var string
	 */
	private static $finalClassName;
	
	/**
	 * @return website_MenuEntry
	 */
	public static function getNewInstance()
	{
		if (self::$finalClassName === null)
		{
			self::$finalClassName = Injection::getFinalClassName(get_class());
		}
		return new self::$finalClassName();
	}
	
	protected function __construct()
	{
		// Should not be called directly. Use getNewInstance().
	}
	
	/**
	 * @var string
	 */
	private $label;
	
	/**
	 * @var string
	 */
	private $url;
	
	/**
	 * @var f_persistentdocument_PersistentDocument
	 */
	private $document;
	
	/**
	 * @var boolean
	 */
	private $container = false;
	
	/** 
	 * @var website_MenuEntry[]
	 */
	private $children = array();
	
	/**
	 * @var integer
	 */
	private $level;
	
	/**
	 * @var boolean
	 */
	private $current = false;
	
	/**
	 * @var boolean
	 */
	private $inPath = false;
	
	/**
	 * @var boolean
	 */
	private $popup = false;
	
	/**
	 * @var string
	 */
	private $onClick;
	
	/**
	 * @var media_persistentdocument_media
	 */
	private $visual;
	
	/**
	 * @return string
	 */
	public function getLabel()
	{
		return $this->label;
	}
	
	/**
	 * @return string
	 */
	public function getLabelAsHtml()
	{
		return f_util_HtmlUtils::textToHtml($this->label);
	}

	/**
	 * @param string $label
	 */
	public function setLabel($label)
	{
		$this->label = $label;
	}

	/**
	 * @return boolean
	 */
	public function hasUrl()
	{
		return !empty($this->url);
	}

	/**
	 * @return string
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * @param string $url
	 */
	public function setUrl($url)
	{
		$this->url = $url;
	}

	/**
	 * @return f_persistentdocument_PersistentDocument
	 */
	public function getDocument()
	{
		return $this->document;
	}

	/**
	 * @param f_persistentdocument_PersistentDocument $document
	 */
	public function setDocument($document)
	{
		$this->document = $document;
	}
	
	/**
	 * @return boolean
	 */
	public function isContainer()
	{
		return $this->container;
	}
	
	/**
	 * @param boolean $container
	 */
	public function setContainer($container)
	{
		$this->container = $container;
	}

	/**
	 * @return boolean
	 */
	public function hasChildren()
	{
		return count($this->children) > 0;
	}
	
	/**
	 * @return website_MenuEntry[]
	 */
	public function getChildren()
	{
		return $this->children;
	}
	
	/**
	 * @return integer
	 */
	public function getChildrenCount()
	{
		return count($this->children);
	}

	/**
	 * @param website_MenuEntry[] $children
	 */
	public function setChildren($children)
	{
		$this->children = $children;
	}

	/**
	 * @return number
	 */
	public function getLevel()
	{
		return $this->level;
	}

	/**
	 * @return number
	 */
	public function getChildrenLevel()
	{
		return $this->level+1;
	}

	/**
	 * @param number $level
	 */
	public function setLevel($level)
	{
		$this->level = $level;
	}

	/**
	 * @return boolean
	 */
	public function isCurrent()
	{
		return $this->current;
	}

	/**
	 * @param boolean $current
	 */
	public function setCurrent($current)
	{
		$this->current = $current;
	}

	/**
	 * @return boolean
	 */
	public function isInPath()
	{
		return $this->inPath;
	}

	/**
	 * @param boolean $inPath
	 */
	public function setInPath($inPath)
	{
		$this->inPath = $inPath;
	}

	/**
	 * @return boolean
	 */
	public function isPopup()
	{
		return $this->popup;
	}

	/**
	 * @param boolean $popup
	 */
	public function setPopup($popup)
	{
		$this->popup = $popup;
	}

	/**
	 * @return string
	 */
	public function getOnClick()
	{
		$onClick = $this->onClick;
		if (!$onClick && $this->isPopup())
		{
			$onClick = 'return accessiblePopup(this);';
		}
		return $onClick;
	}

	/**
	 * @param string $onClick
	 */
	public function setOnClick($onClick)
	{
		$this->onClick = $onClick;
	}

	/**
	 * @return boolean
	 */
	public function hasVisual()
	{
		return $this->visual instanceof media_persistentdocument_media;
	}
	
	/**
	 * @return media_persistentdocument_media
	 */
	public function getVisual()
	{
		return $this->visual;
	}

	/**
	 * @param media_persistentdocument_media $visual
	 */
	public function setVisual($visual)
	{
		$this->visual = $visual;
	}

	/**
	 * @return string
	 */
	public function getNavigationClass()
	{
		if ($this->current)
		{
			return 'current';
		}
		else if ($this->inPath)
		{
			return 'inpath';
		}
		return null;
	}
	
	/**
	 * @return string
	 */
	public function getMenuClass()
	{
		return 'level' . strval($this->level);
	}
	
	/**
	 * @return string
	 */
	public function getLinkClass()
	{
		if ($this->isPopup())
		{
			return 'link popup';
		}
		return 'link';
	}
	
	/**
	 * @return string
	 */
	public function getLinkTitle()
	{
		if ($this->isPopup())
		{
			return '(' . LocaleService::getInstance()->transFO('m.website.frontoffice.in-a-new-window', array('html')) . ')';
		}
		return null;
	}
}