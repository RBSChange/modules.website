<?php
class website_BBCodeParser
{
	
	/**
	 * @var website_BBCodeProfile
	 */
	private $profile = null;
	
	/**
	 * @var string
	 */
	private $bbcode = null;
	
	/**
	 * @var integer
	 */
	private $bbcodelength = null;
	
	/**
	 * @var DOMDocument
	 */
	private $xmlDocument = null;
	
	/**
	 * @var DOMElement
	 */
	private $parentElement = null;
	
	/**
	 * @var integer
	 */
	private $offset;
	
	/**
	 * @var string
	 */
	private $tagName;
	
	/**
	 * @var boolean
	 */
	private $isEndTag;
	
	/**
	 * @var boolean
	 */
	private $isEmptyTag;
	
	/**
	 * @var string
	 */
	private $tagAttribute;
	
	/**
	 * @var boolean
	 */
	private $escapedAttribute;
	
	/**
	 * @var boolean
	 */
	private $escapedTagAttribute;
	
	/**
	 * @var string
	 */
	private $text;
	
	/**
	 * @var boolean
	 */
	private $nobb = false;
	
	/**
	 * @var boolean
	 */
	private $escChar = false;	
	
	/**
	 * @var array
	 */
	private $tags = array();
	
	public function __construct()
	{
		
	}
	
	public function setProfile($profile = 'default')
	{
		if ($this->profile === null || $this->profile->getName() !== $profile)
		{
			$this->tags = array();
			$this->addTagInfo(new website_BBCodeTagInfoNewLine());
			$this->addTagInfo(new website_BBCodeTagInfoTabulation());
			$class = 'website_BBCodeProfile' . ucfirst($profile);
			if (f_util_ClassUtils::classExists($class))
			{
				
				$profile = new $class($this);
			}
			else
			{			
				$profile = new website_BBCodeProfile($this);
			}
			$this->profile = $profile;
		}
	}
	
	/**
	 * @param string $bbcode
	 * @return DOMDocument
	 */
	public function parseBBCode($bbcode, $profile = 'default')
	{
		$this->setProfile($profile);
		$this->bbcode = strval($bbcode);
		$this->bbcodelength = strlen($this->bbcode);
		$this->xmlDocument = new DOMDocument('1.0', 'UTF-8');
		$this->xmlDocument->loadXML('<div data-profile="' . $this->profile->getName() . '"></div>');
		if ($this->bbcodelength)
		{
			$this->parentElement = $this->xmlDocument->documentElement;
			$this->offset = 0;
			$this->text = '';
			$this->clearTag();
			$this->escChar = false;
			$this->nobb = false;
			$this->startParsing();
			
		}
		return $this->xmlDocument;
	}
	
	private function startParsing()
	{
		while ($this->offset < $this->bbcodelength)
		{
			$char = $this->bbcode[$this->offset];
			if ($this->tagAttribute !== null)
			{
				$this->text .= $char;
				if ($this->tagAttribute === '' && $char === '"')
				{
					$this->escapedAttribute = true;
				}
				elseif ($this->escapedAttribute)
				{
					if ($this->escChar)
					{
						$this->escChar = false;
						if ($char === '"')
						{
							$this->tagAttribute .= $char;
						}
						else
						{
							$this->tagAttribute .= "\\" . $char;
						}
					}
					elseif ($char === '"')
					{
						$this->escapedAttribute = false;
					}
					elseif ($char === "\\")
					{
						$this->escChar = true;
					}
					else
					{
						$this->tagAttribute .= $char;
					}
				}
				elseif ($char === '/' && !$this->isEmptyTag)
				{
					$this->isEmptyTag = true;
				}
				elseif ($this->isEmptyTag && $char !== ']')
				{
					$this->tagAttribute .= '/' . $char;
					$this->isEmptyTag = false;
				}
				elseif ($char === ']')
				{
					$this->appendTag();
				}
				else
				{
					$this->tagAttribute .= $char;
				}
			}
			elseif ($this->tagName !== null)
			{
				$this->text .= $char;
				if ($char === ' ')
				{
					//Ignore
				}
				elseif ($char === '/')
				{
					if ($this->tagName === '')
					{
						$this->isEndTag = true;
						$this->isEmptyTag = false;
					}
					else
					{
						$this->isEmptyTag = true;
						$this->isEndTag = false;
					}
				}
				elseif ($this->isEmptyTag && $char !== ']')
				{
					$this->addText();
				}
				elseif ($char === '=')
				{
					$tagInfo = $this->getTagInfo($this->tagName);
					if ($tagInfo)
					{
						$this->tagAttribute = '';
					}
					else
					{
						$this->addText();
					}
				}
				elseif ($char === ']')
				{
					$tagInfo = $this->getTagInfo($this->tagName);
					if ($tagInfo)
					{
						$this->appendTag();
					}
					else
					{
						$this->addText();
					}
				}
				elseif ($this->checkTagName($this->tagName . $char))
				{
					$this->tagName .= $char;
				}
				else
				{
					$this->addText();
				}
			}
			else
			{
				if ($char === "\r")
				{
					//Ignore
				}
				elseif ($char === "\n" && !$this->nobb)
				{
					$this->addText();
					$this->tagName = 'newline';
					$this->appendTag();
				}
				elseif ($char === "\t" && !$this->nobb)
				{
					$this->addText();
					$this->tagName = 'tabulation';
					$this->appendTag();
				}
				elseif ($char === "[")
				{
					$this->addText();
					$this->tagName = '';
					$this->text = $char;
				}
				else
				{
					$this->text .= $char;
				}
			}
			$this->offset ++;
		}
		
		if (strlen($this->text))
		{
			$this->addText();
		}
	}
	
	private function addText()
	{
		$text = $this->text;
		if ($text !== '')
		{
			$this->parentElement->appendChild($this->xmlDocument->createTextNode($text));
			$this->text = '';
		}
		$this->clearTag();
	}
	
	private function clearTag()
	{
		$this->tagAttribute = null;
		$this->escapedAttribute = false;
		$this->tagName = null;
		$this->isEmptyTag = false;
		$this->isEndTag = false;
	}
	
	private function appendTag()
	{
		$tagInfo = $this->getTagInfo($this->tagName);
		if ($this->nobb)
		{
			if ($this->isEndTag && $tagInfo->getRawContent())
			{
				$this->nobb = false;
				$this->endTag($tagInfo);
			}
		}
		else
		{
			if ($this->isEndTag)
			{
				$this->endTag($tagInfo);
			}
			else
			{
				if ($tagInfo->getRawContent())
				{
					$this->nobb = true;
				}
				$this->openTag($tagInfo);
			}
		}
		$this->addText();
	}
	
	/**
	 * @param website_BBCodeTagInfo $tagInfo
	 */
	private function openTag($tagInfo)
	{
		$element = $this->xmlDocument->createElement($tagInfo->getXmlNodeName());
		$element->setAttribute('data-bbcode', $tagInfo->getTagName());
		$this->parentElement->appendChild($element);
		if ($this->tagAttribute !== null)
		{
			$element->setAttribute('data-bbcode-attr', $this->tagAttribute);
		}
		
		if (! $tagInfo->getEmptyTag() && ! $this->isEmptyTag)
		{
			$this->parentElement = $element;
		}
		$this->text = '';
	}
	
	/**
	 * @param website_BBCodeTagInfo $tagInfo
	 */
	private function endTag($tagInfo)
	{
		$tagName = $tagInfo->getTagName();
		$currentElement = $this->parentElement;
		while ($currentElement->hasAttribute('data-bbcode'))
		{
			if ($currentElement->getAttribute('data-bbcode') === $tagName)
			{
				$this->parentElement = $currentElement->parentNode;
				$this->text = '';
				break;
			}
			$currentElement = $currentElement->parentNode;
		}
	}
	
	/**
	 * @param DOMDocument $document
	 * @return string
	 */
	public function convertToXml($document)
	{
		$this->xmlDocument = $document;
		$this->parentElement = $this->xmlDocument->documentElement;
		$this->setProfile($this->parentElement->getAttribute('data-profile'));
		foreach ($this->xmlDocument->documentElement->getElementsByTagName('*') as $element)
		{
			if ($element->hasAttribute('data-bbcode'))
			{
				$tagInfo = $this->getTagInfo($element->getAttribute('data-bbcode'));
				if ($tagInfo)
				{
					$tagInfo->normalizeXml($element);
				}
			}
		}
		$this->xmlDocument->normalizeDocument();
		return $this->xmlDocument->saveXML($this->parentElement);
	}
	
	/**
	 * @param DOMDocument $document
	 * @return string
	 */	
	public function convertToBBcode($document)
	{
		$bbcode = array();
		$this->xmlDocument = $document;
		$this->parentElement = $this->xmlDocument->documentElement;
		$this->setProfile($this->parentElement->getAttribute('data-profile'));
		foreach ($this->parentElement->childNodes as $node) 
		{
			$bbcode[] = $this->convertNodeToBBcode($node);
		}
		
		return implode('', $bbcode);
	}
	
	/**
	 * @param DOMNode $node
	 * @return string
	 */
	private function convertNodeToBBcode($node)
	{
		$bbcode = array();
		if ($node->nodeType === XML_ELEMENT_NODE)
		{
			if ($node->hasAttribute('data-bbcode'))
			{
				$tagInfo = $this->getTagInfo($node->getAttribute('data-bbcode'));
				if ($tagInfo)
				{
					$beginStr = ''; $endStr = '';
					$tagInfo->toBBCode($node, $beginStr, $endStr);
					$bbcode[] = $beginStr;
					if ($endStr !== '' && $node->hasChildNodes())
					{
						foreach ($node->childNodes as $subNode) 
						{
							$bbcode[] = $this->convertNodeToBBcode($subNode);
						}
					}
					$bbcode[] = $endStr;
				}
			}
		}
		elseif ($node->nodeType === XML_TEXT_NODE)
		{
			$bbcode[] = $node->nodeValue;
		}
		return implode('', $bbcode);
	}
	
	/**
	 * @param DOMDocument $document
	 * @return string
	 */
	public function convertToHtml($document)
	{
		$this->xmlDocument = $document;
		$this->parentElement = $this->xmlDocument->documentElement;
		$this->setProfile($this->parentElement->getAttribute('data-profile'));
		$xhtmlDoc = new DOMDocument('1.0', 'UTF-8');
		$xhtmlDoc->loadXML('<div class="bbcode ' . $this->profile->getName() . '"></div>');
		foreach ($this->parentElement->childNodes as $node) 
		{
			$this->convertNodeToHtml($node, $xhtmlDoc->documentElement, $xhtmlDoc);
		}
		return $xhtmlDoc->saveXML($xhtmlDoc->documentElement);
	}
	
	/**
	 * @param DOMNode $node
	 * @param DOMElement $xhtmlParentElement;
	 * @param DOMDocument $xhtmlDoc
	 */
	private function convertNodeToHtml($node, $xhtmlParentElement, $xhtmlDoc)
	{
		if ($node->nodeType === XML_ELEMENT_NODE)
		{
			if ($node->hasAttribute('data-bbcode'))
			{
				$tagInfo = $this->getTagInfo($node->getAttribute('data-bbcode'));
				if ($tagInfo)
				{
					$newParent = $tagInfo->toHtml($node, $xhtmlParentElement);
					if ($newParent!== null && $node->hasChildNodes())
					{
						foreach ($node->childNodes as $subNode) 
						{
							$this->convertNodeToHtml($subNode, $newParent, $xhtmlDoc);
						}
					}
				}
			}
		}
		elseif ($node->nodeType === XML_TEXT_NODE)
		{
			$xhtmlParentElement->appendChild($xhtmlDoc->createTextNode($node->nodeValue));
		}
	}
	
	/**
	 * @param string $value
	 * @return boolean
	 */
	private function checkTagName($value)
	{
		if (isset($this->tags[$value]))
		{
			return true;
		}
		foreach ($this->tags as $tagName => $tagInfo)
		{
			if (strpos($tagName, $value) === 0)
			{
				return true;
			}
		}
		return false;
	}
	
	/**
	 * @return website_BBCodeTagInfo || null
	 */
	private function getTagInfo($tagName)
	{
		if (isset($this->tags[$tagName]))
		{
			return $this->tags[$tagName];
		}
		return null;
	}
	
	/**
	 * @param website_BBCodeTagInfo $tagInfo
	 */
	public function addTagInfo($tagInfo)
	{
		$this->tags[$tagInfo->getTagName()] = $tagInfo;
	}
	
	/**
	 * @param string $tagName
	 */
	public function removeTagInfo($tagName)
	{
		if (isset($this->tags[$tagName]))
		{
			unset($this->tags[$tagName]);
		}
	}
}

class website_BBCodeProfile
{
	protected $name = 'default';
	
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param website_BBCodeParser $bbcodeParser
	 */
	protected function addProjectConfig($bbcodeParser)
	{
		$bbcodes = Framework::getConfigurationValue('modules/website/bbcodes', array());
		foreach ($bbcodes as $className) 
		{
			if (f_util_ClassUtils::classExists($className))
			{
				$bbcodeParser->addTagInfo(new $className());
			}
			else
			{
				Framework::error(__METHOD__ . ' class not found: ' . $className);
			}
		}
	}
	
	/**
	 * @param website_BBCodeParser $bbcodeParser
	 */
	public function __construct($bbcodeParser)
	{
		$bbcodeParser->addTagInfo(new website_BBCodeTagInfo('nobb', 'pre', false, true));
		$bbcodeParser->addTagInfo(new website_BBCodeTagInfo('code', 'pre', false, true));
		$bbcodeParser->addTagInfo(new website_BBCodeTagInfo('quote', 'blockquote'));
		$bbcodeParser->addTagInfo(new website_BBCodeTagInfo('b', 'strong'));
		$bbcodeParser->addTagInfo(new website_BBCodeTagInfo('i', 'em'));
		$bbcodeParser->addTagInfo(new website_BBCodeTagInfoU());
		$bbcodeParser->addTagInfo(new website_BBCodeTagInfo('s', 'del'));
		$bbcodeParser->addTagInfo(new website_BBCodeTagInfoImg());
		$bbcodeParser->addTagInfo(new website_BBCodeTagInfoUrl());
		$bbcodeParser->addTagInfo(new website_BBCodeTagInfoAlign());
		$bbcodeParser->addTagInfo(new website_BBCodeTagInfo('list', 'ul'));
		$bbcodeParser->addTagInfo(new website_BBCodeTagInfo('item', 'li'));
		$bbcodeParser->addTagInfo(new website_BBCodeTagInfoColor());	
		$this->addProjectConfig($bbcodeParser);
	}
}

class website_BBCodeTagInfo
{
	/**
	 * @var string
	 */
	protected $tagName;
	
	/**
	 * @var boolean
	 */
	protected $emptyTag;
	
	/**
	 * @var boolean
	 */
	protected $rawContent;
	
	/**
	 * @var string
	 */
	protected $xmlNodeName;
	
	public function __construct($tagName, $xmlNodeName, $emptyTag = false, $rawContent = false)
	{
		$this->tagName = $tagName;
		$this->xmlNodeName = $xmlNodeName;
		$this->emptyTag = $emptyTag;
		$this->rawContent = $rawContent;
	}
	
	/**
	 * @return string
	 */
	public function getXmlNodeName()
	{
		return $this->xmlNodeName;
	}
	
	/**
	 * @return the $tagName
	 */
	public function getTagName()
	{
		return $this->tagName;
	}
	
	/**
	 * @param DOMElement $xmlElement
	 * @return string || null
	 */
	public function getTagAttribute($xmlElement)
	{
		return $xmlElement->hasAttribute('data-bbcode-attr') ? $xmlElement->getAttribute('data-bbcode-attr') : null;
	}
		
	/**
	 * @return boolean
	 */
	public function getEmptyTag()
	{
		return $this->emptyTag;
	}
	
	/**
	 * @return boolean
	 */
	public function getRawContent()
	{
		return $this->rawContent;
	}
	
	/**
	 * @param DOMElement $xmlElement
	 */
	public function normalizeXml($xmlElement)
	{

	}
	
	/**
	 * @param DOMElement $xmlElement
	 * @param DOMElement $xhtmlParent
	 * @return DOMElement Xhtml parent for content
	 */
	public function toHtml($xmlElement, $xhtmlParent)
	{
		$element = $xhtmlParent->ownerDocument->createElement($this->getXmlNodeName());
		$xhtmlParent->appendChild($element);
		if ($this->emptyTag)
		{
			return null;
		}
		return $element;
	}
	
	/**
	 * @param DOMElement $xmlElement
	 * @param string $beginStr
	 * @param string $endStr
	 */
	public function toBBCode($xmlElement, &$beginStr, &$endStr)
	{
		$endStr = ($this->emptyTag) ? '' : '[/' . $this->tagName . ']';
		$attr = $this->getTagAttribute($xmlElement);
		if ($attr !== null)
		{
			$beginStr = '[' . $this->tagName . '="' . str_replace('"', '\"', $attr) .'"]';
		}
		else
		{
			$beginStr = '[' . $this->tagName . ']';
		}
	}
}

class website_BBCodeTagInfoNewLine extends website_BBCodeTagInfo
{
	public function __construct()
	{
		parent::__construct('newline', 'br', true);
	}
	
	public function toBBCode($xmlElement, &$beginStr, &$endStr)
	{
		$endStr = '';
		$beginStr = "\n";
	}	
}

class website_BBCodeTagInfoTabulation extends website_BBCodeTagInfo
{
	public function __construct()
	{
		parent::__construct('tabulation', 'span', true);
	}
	
	/**
	 * @param DOMElement $xmlElement
	 * @param string $beginStr
	 * @param string $endStr
	 */	
	public function toBBCode($xmlElement, &$beginStr, &$endStr)
	{
		$endStr = '';
		$beginStr = "\t";
	}
	
	/**
	 * @param DOMElement $xmlElement
	 * @param DOMElement $xhtmlParent
	 * @return DOMElement Xhtml parent for content
	 */	
	public function toHtml($xmlElement, $xhtmlParent)
	{
		$doc = $xhtmlParent->ownerDocument;
		$element = $doc->createElement($this->getXmlNodeName());
		$element->setAttribute('class', 'tabulation');
		$element->appendChild($doc->createTextNode(' '));
		$xhtmlParent->appendChild($element);
		return null;
	}
}

class website_BBCodeTagInfoU extends website_BBCodeTagInfo
{
	public function __construct()
	{
		parent::__construct('u', 'span', false);
	}
	
	/**
	 * @param DOMElement $xmlElement
	 */
	public function normalizeXml($xmlElement)
	{
		$xmlElement->setAttribute('style', 'text-decoration: underline;');
	}

	/**
	 * @param DOMElement $xmlElement
	 * @param DOMElement $xhtmlParent
	 * @return DOMElement Xhtml parent for content
	 */	
	public function toHtml($xmlElement, $xhtmlParent)
	{
		$elem = parent::toHtml($xmlElement, $xhtmlParent);
		if ($elem)
		{
			$elem->setAttribute('style', 'text-decoration: underline;');
		}
		return $elem;
	}	
}

class website_BBCodeTagInfoImg extends website_BBCodeTagInfo
{
	public function __construct()
	{
		parent::__construct('img', 'img', false);
	}
	
	/**
	 * @param DOMElement $xmlElement
	 */
	public function normalizeXml($xmlElement)
	{
		$src = $this->getTagAttribute($xmlElement);
		if ($src === null)
		{
			$src = $xmlElement->textContent;
			if (empty($src)) {$src = '/media/frontoffice/pixel.gif';}
			$xmlElement->setAttribute('data-bbcode-attr', $src);
			while ($xmlElement->hasChildNodes()) {$xmlElement->removeChild($xmlElement->firstChild);}
		}
		$xmlElement->setAttribute('src', $src);
	}
	
	/**
	 * @param DOMElement $xmlElement
	 * @param DOMElement $xhtmlParent
	 * @return DOMElement Xhtml parent for content
	 */
	public function toHtml($xmlElement, $xhtmlParent)
	{
		$elem = parent::toHtml($xmlElement, $xhtmlParent);
		$src = $this->getTagAttribute($xmlElement);
		$alt = $xmlElement->textContent;
		$elem->setAttribute('src', $src);
		$elem->setAttribute('alt', $alt);
		if ($alt) {$elem->setAttribute('title', $alt);}
		return null;
	}	
}

class website_BBCodeTagInfoUrl extends website_BBCodeTagInfo
{
	public function __construct()
	{
		parent::__construct('url', 'a', false);
	}
	
	/**
	 * @param DOMElement $xmlElement
	 */
	public function normalizeXml($xmlElement)
	{
		$href = $this->getTagAttribute($xmlElement);
		if ($href === null)
		{
			$href = $xmlElement->textContent;
			if (empty($href)) {$href = 'about:blank';}
			$xmlElement->setAttribute('data-bbcode-attr', $href);
		}
		$xmlElement->setAttribute('href', $href);
	}
	
		/**
	 * @param DOMElement $xmlElement
	 * @param string $beginStr
	 * @param string $endStr
	 */
	public function toBBCode($xmlElement, &$beginStr, &$endStr)
	{
		$href = $this->getTagAttribute($xmlElement);
		if ($xmlElement->hasChildNodes() && $xmlElement->textContent === $href)
		{
			$beginStr = '[' . $this->tagName . ']';
		}
		else
		{
			$beginStr = '[' . $this->tagName . '="' . str_replace('"', '\"', $href) .'"]';
		}
		$endStr = '[/' . $this->tagName . ']';
	}

	/**
	 * @param DOMElement $xmlElement
	 * @param DOMElement $xhtmlParent
	 * @return DOMElement Xhtml parent for content
	 */
	public function toHtml($xmlElement, $xhtmlParent)
	{
		$elem = parent::toHtml($xmlElement, $xhtmlParent);
		$elem->setAttribute('class', 'link');
		$elem->setAttribute('target', '_blank');
		$href = $this->getTagAttribute($xmlElement);
		$elem->setAttribute('href', $href);
		return $elem;
	}	
}

class website_BBCodeTagInfoAlign extends website_BBCodeTagInfo
{
	public function __construct()
	{
		parent::__construct('align', 'div', false);
	}

	/**
	 * @param DOMElement $xmlElement
	 */
	public function normalizeXml($xmlElement)
	{
		$align = $this->getTagAttribute($xmlElement);
		if ($align === null || !in_array($align, array('left','right','center','justify')));
		{
			$align = 'left';
			$xmlElement->setAttribute('data-bbcode-attr', $align);
		}
		$xmlElement->setAttribute('class', 'align-' . $align);
	}
	
	/**
	 * @param DOMElement $xmlElement
	 * @param DOMElement $xhtmlParent
	 * @return DOMElement Xhtml parent for content
	 */
	public function toHtml($xmlElement, $xhtmlParent)
	{
		$elem = parent::toHtml($xmlElement, $xhtmlParent);
		$elem->setAttribute('class', 'align-' . $this->getTagAttribute($xmlElement));
		return $elem;
	}	
}

class website_BBCodeTagInfoColor extends website_BBCodeTagInfo
{
	public function __construct()
	{
		parent::__construct('color', 'span', false);
	}
	
	/**
	 * @param DOMElement $xmlElement
	 */
	public function normalizeXml($xmlElement)
	{
		$color = $this->getTagAttribute($xmlElement);
		if (empty($color) || (!preg_match('/(^#([0-9a-fA-F]{3}){1,2}$)|(^[a-zA-Z]{3,20}$)/', $color)))
		{
			$color = 'inherit';
			$xmlElement->setAttribute('data-bbcode-attr', $color);
		}
		$xmlElement->setAttribute('style', 'color:' . $color .';');
	}
	
	/**
	 * @param DOMElement $xmlElement
	 * @param DOMElement $xhtmlParent
	 * @return DOMElement Xhtml parent for content
	 */
	public function toHtml($xmlElement, $xhtmlParent)
	{
		$elem = parent::toHtml($xmlElement, $xhtmlParent);
		$elem->setAttribute('style', 'color:' . $this->getTagAttribute($xmlElement) .';');
		return $elem;
	}		
}