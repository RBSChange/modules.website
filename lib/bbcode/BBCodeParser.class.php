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
	private $inUrl = false;
	
	/**
	 * @var boolean
	 */
	private $needSpaceToStartUrl = false;
	
	/**
	 * @var string
	 */
	private $url;
	
	/**
	 * @var boolean
	 */
	private $escChar = false;	
	
	/**
	 * @var array
	 */
	private $tags = array();

	/**
	 * @var array
	 */
	private $smileList;
	
	public function __construct()
	{
		
	}
	
	/**
	 * @param string $moduleName
	 * @return string
	 */
	public function getModuleProfile($moduleName)
	{
		return Framework::getConfigurationValue('modules/' . $moduleName . '/bbcodeProfile', 'default');
	}
	
	/**
	 * @param string $profile
	 */
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
	protected function parseBBCode($bbcode, $profile = 'default')
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
			$this->smile = '';
			$this->clearTag();
			$this->escChar = false;
			$this->nobb = false;
			$this->inUrl = false;
			$this->needSpaceToStartUrl = false;
			$this->startParsing();
			
		}
		return $this->xmlDocument;
	}
	
	/**
	 * @param string $xmlString
	 * @return DOMDocument
	 */
	protected function parseXml($xmlString)
	{
		$dom = new DOMDocument('1.0', 'UTF-8');
		if ($dom->loadXML($xmlString) === true)
		{
			return $dom;
		}
		
		if (Framework::isWarnEnabled())
		{
			Framework::warn(__METHOD__ . ' Invalid xml string.');
			Framework::warn(f_util_ProcessUtils::getBackTrace());
		}
		$dom = $this->parseBBCode($xmlString);
		$dom->documentElement->setAttribute('class', 'error');
		return $dom;
	}
	
	/**
	 * @param string $bbcode
	 * @param string $profile
	 * @return string XML string
	 */
	public function convertBBCodeToXml($bbcode, $profile = 'default')
	{
		if (f_util_StringUtils::isEmpty($bbcode))
		{
			return null;
		}
		return $this->convertToXml($this->parseBBCode($bbcode, $profile));
	}
	
	/**
	 * @param string $bbcode
	 * @param string $profile
	 * @return string XML string
	 */
	public function convertBBCodeToHtml($bbcode, $profile = 'default')
	{
		if (f_util_StringUtils::isEmpty($bbcode))
		{
			return null;
		}
		return $this->convertXmlToHtml($this->convertBBCodeToXml($bbcode, $profile));
	}
	
	/**
	 * @param string $xmlString
	 * @return string HTML string
	 */
	public function convertXmlToHtml($xmlString)
	{
		if (f_util_StringUtils::isEmpty($xmlString))
		{
			return null;
		}
		return $this->convertToHtml($this->parseXml($xmlString));
	}
	
	/**
	 * @param string $xmlString
	 * @return string BBCode string
	 */
	public function convertXmlToBBCode($xmlString)
	{
		if (f_util_StringUtils::isEmpty($xmlString))
		{
			return null;
		}
		return $this->convertToBBCode($this->parseXml($xmlString));
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
				if (!$this->nobb)
				{
					$smile = $this->checkSmile($this->text, $char);
					if ($smile)
					{
						$convert = true;
						foreach ($this->checkLargerSmiles($smile) as $smileEnd)
						{
							if ($smileEnd === substr($this->bbcode, $this->offset+1, strlen($smileEnd)))
							{
								$convert = false;
								break;
							}
						}
						
						if ($convert)
						{
							// Add part of $this->text.
							$this->text = substr($this->text, 0, 1 - strlen($smile));
							
							$this->addText();
							$this->tagName = 'smile';
							$this->tagAttribute = $smile;
							$this->appendTag();
							
							$this->offset ++;
							continue;
						}
					}
				}
				
				if ($char === "/" && !$this->nobb && !$this->inUrl)
				{
					// Detect URLs.
					$startURL = $this->checkStartURL($this->text);
					if ($startURL !== null)
					{
						$this->text = substr($this->text, 0, -strlen($startURL));
						$this->addText();
						$this->text = $startURL;
						$this->inUrl = true;
				}
					$this->text .= $char;
				}
				elseif ($char === "\r")
				{
					// Ignore.
				}
				elseif ($char === "\n" && !$this->nobb)
				{
					$this->addText();
					$this->tagName = 'newline';
					$this->appendTag();
					$this->needSpaceToStartUrl = false;
				}
				elseif ($char === "\t" && !$this->nobb)
				{
					$this->addText();
					$this->tagName = 'tabulation';
					$this->appendTag();
					$this->needSpaceToStartUrl = false;
				}
				elseif ($char === " " && !$this->nobb)
				{
					$this->addText();
					$this->text = $char;
				}
				elseif ($char === "[" && !$this->inUrl)
				{
					$this->addText();
					$this->tagName = '';
					$this->text = $char;
					$this->needSpaceToStartUrl = true;
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
	
	/**
	 * @return array
	 */
	private function getSmiles()
	{
		if ($this->smileList === null)
		{
			$this->smileList = array();
			$smileTag = $this->getTagInfo('smile');
			if ($smileTag instanceof website_BBCodeTagInfoSmile)
			{
				foreach ($smileTag->getSmileCodes() as $smile) 
				{
					$endChar = substr($smile, -1);
					$baseSmile = substr($smile, 0, strlen($smile) - 1);
					$this->smileList[$endChar][] = $baseSmile;
				}
			}
		}
		return $this->smileList;
	}
	
	/**
	 * @param string $text
	 * @return string or null
	 */
	private function checkSmile($text, $endChar)
	{
		$smiles = $this->getSmiles();
		if (isset($smiles[$endChar]))
		{
			foreach ($smiles[$endChar] as $baseSmile) 
			{
				if (substr($text, -strlen($baseSmile)) === $baseSmile)
				{
					return $baseSmile.$endChar;
				}
			}			
		}
		return null;
	}
	
	/**
	 * @return string[]
	 */
	private $largerSmiles = array();
	
	/**
	 * @param string $smile
	 * @return string[]
	 */
	private function checkLargerSmiles($smile)
	{
		if (!array_key_exists($smile, $this->largerSmiles))
		{
			$this->largerSmiles[$smile] = array();
			foreach ($this->getSmiles() as $smiles)
			{
				foreach ($smiles as $asmile)
				{
					if (f_util_StringUtils::beginsWith($asmile, $smile, f_util_StringUtils::CASE_SENSITIVE))
					{
						$this->largerSmiles[$smile][] = substr($asmile, strlen($smile));
					}
				}
			}
		}
		return $this->largerSmiles[$smile];
	}
	
	/**
	 * @var string[]
	 */
	private $startUrlArray = array('http:/', 'https:/');
	
	/**
	 * @return string[]
	 */
	protected function getStartUrlArray()
	{
		return $this->startUrlArray;
	}
	
	/**
	 * @param string $text
	 * @return string or null
	 */
	private function checkStartUrl($text)
	{
		foreach ($this->getStartUrlArray() as $urlStart)
		{
			if (!$this->needSpaceToStartUrl && ($text == $urlStart))
			{
				return $urlStart;
			}
			
			$tempStart = ' ' . $urlStart;
			if (substr($text, -strlen($tempStart)) == $tempStart)
			{
				return $urlStart;
			}
		}
		return null;
	}
	
	private function addText()
	{
		$text = $this->text;
		if ($text !== '')
		{
			if ($this->inUrl)
			{	
				$this->inUrl = false;
				$tagInfo = $this->getTagInfo('url');
				$this->openTag($tagInfo);
				$this->parentElement->appendChild($this->xmlDocument->createTextNode($text));
				$this->endTag($tagInfo);
			}
			else
			{
				$this->parentElement->appendChild($this->xmlDocument->createTextNode($text));
			}
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
	protected function convertToXml($document)
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
	protected function convertToBBCode($document)
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
	protected function convertToHtml($document)
	{
		$this->xmlDocument = $document;
		$this->parentElement = $this->xmlDocument->documentElement;
		$this->setProfile($this->parentElement->getAttribute('data-profile'));
		$classes = array('bbcode', $this->profile->getName());
		if ($this->parentElement->hasAttribute('class'))
		{
			$classes[] = $this->parentElement->getAttribute('class');
		}
		$xhtmlDoc = new DOMDocument('1.0', 'UTF-8');
		$xhtmlDoc->loadXML('<div class="' . implode(' ', $classes) . '"></div>');
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
	
	/**
	 * @return array
	 */
	public function getTagInfos()
	{
		return $this->tags;
	}
}

class website_BBCodeProfile
{
	/**
	 * @var string
	 */
	protected $name = 'default';
	
	/**
	 * @return string
	 */
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
		$bbcodeParser->addTagInfo(new website_BBCodeTagInfo('b', 'strong', 'richtext/bold'));
		$bbcodeParser->addTagInfo(new website_BBCodeTagInfo('i', 'em', 'richtext/italic'));
		$bbcodeParser->addTagInfo(new website_BBCodeTagInfoU());
		$bbcodeParser->addTagInfo(new website_BBCodeTagInfo('s', 'del', 'richtext/strike'));
		$bbcodeParser->addTagInfo(new website_BBCodeTagInfo('sup', 'sup', 'richtext/superscript'));
		$bbcodeParser->addTagInfo(new website_BBCodeTagInfo('sub', 'sub', 'richtext/subscript'));
		$bbcodeParser->addTagInfo(new website_BBCodeTagInfoAbbr());
		$bbcodeParser->addTagInfo(new website_BBCodeTagInfoBig());
		$bbcodeParser->addTagInfo(new website_BBCodeTagInfoSmall());
		$bbcodeParser->addTagInfo(new website_BBCodeTagInfoAlign());
		$bbcodeParser->addTagInfo(new website_BBCodeTagInfoList());
		$bbcodeParser->addTagInfo(new website_BBCodeTagInfoListItem());
		$bbcodeParser->addTagInfo(new website_BBCodeTagInfoQuote());
		$bbcodeParser->addTagInfo(new website_BBCodeTagInfo('code', 'pre', 'richtext/code', false, true));
		$bbcodeParser->addTagInfo(new website_BBCodeTagInfoNoBB());
		$bbcodeParser->addTagInfo(new website_BBCodeTagInfoUrl());
		$bbcodeParser->addTagInfo(new website_BBCodeTagInfoDoc());
		$bbcodeParser->addTagInfo(new website_BBCodeTagInfoImg());
		$bbcodeParser->addTagInfo(new website_BBCodeTagInfoHr());
		$bbcodeParser->addTagInfo(new website_BBCodeTagInfoColor());
		$bbcodeParser->addTagInfo(new website_BBCodeTagInfoSmile());
		$this->addProjectConfig($bbcodeParser);
	}
}

class website_BBCodeProfileEmpty extends website_BBCodeProfile
{
	/**
	 * @var string
	 */
	protected $name = 'empty';
	
	/**
	 * @param website_BBCodeParser $bbcodeParser
	 */
	public function __construct($bbcodeParser)
	{
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
	
	/**
	 * @var string
	 */
	protected $icon;
	
	public function __construct($tagName, $xmlNodeName, $icon = null, $emptyTag = false, $rawContent = false)
	{
		$this->tagName = $tagName;
		$this->xmlNodeName = $xmlNodeName;
		$this->icon = $icon;
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
	
	/**
	 * @return string
	 */
	protected function getLabelKey()
	{
		return 'm.website.bbeditor.' . $this->getTagName();
	}
	
	/**
	 * @return string
	 */
	protected function getIconName()
	{
		return $this->icon;
	}
	
	/**
	 * @return string
	 */
	protected function getClassName()
	{
		return 'button';
	}
	
	/**
	 * @return string
	 */
	protected function getOpenTag()
	{
		return '[' . $this->getTagName() . ']';
	}
	
	/**
	 * @return string
	 */
	protected function getCloseTag()
	{
		return '[/' . $this->getTagName() . ']';
	}
	
	/**
	 * @return array
	 */
	public function getInfosForJS()
	{
		return array(array(
			'label' => '${trans:' . $this->getLabelKey() . ',ucf}',
			'icon' => $this->getIconName(),
			'openTag' => $this->getOpenTag(),
			'closeTag' => $this->getCloseTag(),
			'className' => $this->getClassName()
		));
	}
}

class website_BBCodeTagInfoNewLine extends website_BBCodeTagInfo
{
	public function __construct()
	{
		parent::__construct('newline', 'br', null, true);
	}
	
	/**
	 * @param DOMElement $xmlElement
	 * @param DOMElement $xhtmlParent
	 * @return DOMElement Xhtml parent for content
	 */	
	public function toBBCode($xmlElement, &$beginStr, &$endStr)
	{
		$endStr = '';
		$beginStr = "\n";
	}
	
	/**
	 * @return array
	 */
	public function getInfosForJS()
	{
		return null;
	}
}

class website_BBCodeTagInfoTabulation extends website_BBCodeTagInfo
{
	public function __construct()
	{
		parent::__construct('tabulation', 'span', null, true);
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
	
	/**
	 * @return array
	 */
	public function getInfosForJS()
	{
		return null;
	}
}

class website_BBCodeTagInfoSmile extends website_BBCodeTagInfo
{
	public function __construct()
	{
		parent::__construct('smile', 'img', null, true);
	}

	public function getSmileCodes()
	{
		return array(':D', ';)', ':p', ':(',  ':)');
	}

	/**
	 * @param DOMElement $xmlElement
	 * @param string $beginStr
	 * @param string $endStr
	 */
	public function toBBCode($xmlElement, &$beginStr, &$endStr)
	{
		$beginStr = $this->getTagAttribute($xmlElement);
		$endStr = '';
	}

	/**
	 * @param DOMElement $xmlElement
	 */
	public function normalizeXml($xmlElement)
	{
		$smile = $this->getTagAttribute($xmlElement);
		$key = array_search($smile, $this->getSmileCodes(), true);
		$filename = (is_numeric($key)) ? 'smile/' . ($key +1) . '.gif' : $key;
		$xmlElement->setAttribute('filename', $filename);
	}

	/**
	 * @param DOMElement $xmlElement
	 * @param DOMElement $xhtmlParent
	 * @return DOMElement Xhtml parent for content
	 */
	public function toHtml($xmlElement, $xhtmlParent)
	{
		$elem = $xhtmlParent->appendChild($xhtmlParent->ownerDocument->createElement($this->getXmlNodeName()));
		$smile = $this->getTagAttribute($xmlElement);
		$elem->setAttribute('src', MediaHelper::getFrontofficeStaticUrl($xmlElement->getAttribute('filename')));
		$elem->setAttribute('alt', $smile);
		$elem->setAttribute('class', 'image smile');
		return null;
	}

	public function getInfosForJS()
	{
		return array();
	}
}

class website_BBCodeTagInfoHr extends website_BBCodeTagInfo
{
	public function __construct()
	{
		parent::__construct('hr', 'hr', 'richtext/rule', true);
	}

	/**
	 * @param DOMElement $xmlElement
	 * @param string $beginStr
	 * @param string $endStr
	 */
	public function toBBCode($xmlElement, &$beginStr, &$endStr)
	{
		$beginStr = '[' . $this->tagName . '/]';
		$endStr = '';
	}
	
	/**
	 * @return string
	 */
	protected function getOpenTag()
	{
		return '';
	}

	/**
	 * @return string
	 */
	protected function getCloseTag()
	{
		return '[hr/]';
	}
}

class website_BBCodeTagInfoNoBB extends website_BBCodeTagInfo
{
	public function __construct()
	{
		parent::__construct('nobb', 'none', 'no-bb', false, true);
	}
	
	/**
	 * @param DOMElement $xmlElement
	 * @param DOMElement $xhtmlParent
	 * @return DOMElement Xhtml parent for content
	 */	
	public function toHtml($xmlElement, $xhtmlParent)
	{
		return $xhtmlParent;
	}
}

class website_BBCodeTagInfoU extends website_BBCodeTagInfo
{
	public function __construct()
	{
		parent::__construct('u', 'span', 'richtext/underline');
	}
	
	/**
	 * @param DOMElement $xmlElement
	 */
	public function normalizeXml($xmlElement)
	{
		$xmlElement->setAttribute('class', 'underline');
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
			$elem->setAttribute('class', 'underline');
		}
		return $elem;
	}
}

class website_BBCodeTagInfoBig extends website_BBCodeTagInfo
{
	public function __construct()
	{
		parent::__construct('big', 'span', 'richtext/big');
	}
	
	/**
	 * @param DOMElement $xmlElement
	 */
	public function normalizeXml($xmlElement)
	{
		$xmlElement->setAttribute('class', 'big');
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
			$elem->setAttribute('class', 'big');
		}
		return $elem;
	}
}

class website_BBCodeTagInfoSmall extends website_BBCodeTagInfo
{
	public function __construct()
	{
		parent::__construct('small', 'span', 'richtext/small');
	}
	
	/**
	 * @param DOMElement $xmlElement
	 */
	public function normalizeXml($xmlElement)
	{
		$xmlElement->setAttribute('class', 'small');
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
			$elem->setAttribute('class', 'small');
		}
		return $elem;
	}
}

class website_BBCodeTagInfoImg extends website_BBCodeTagInfo
{
	public function __construct()
	{
		parent::__construct('img', 'img', 'photo');
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
		$elem->setAttribute('class', 'image');
		if ($alt) {$elem->setAttribute('title', $alt);}
		return null;
	}

	/**
	 * @return string
	 */
	protected function getOpenTag()
	{
		return '[img="@Src@"]';
	}
	
	/**
	 * @return array
	 */
	public function getInfosForJS()
	{
		$infos = f_util_ArrayUtils::firstElement(parent::getInfosForJS());
		$infos['paramSrc'] = '${trans:m.website.bbeditor.param-src,ucf}';
		return array($infos);
	}
}

class website_BBCodeTagInfoUrl extends website_BBCodeTagInfo
{
	public function __construct()
	{
		parent::__construct('url', 'a', 'richtext/link');
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
			
			// Shorten long urls.
			if (f_util_StringUtils::strlen($href) > 50)
			{
				$shortUrl = f_util_StringUtils::substr($href, 0, 20) . '.....' . f_util_StringUtils::substr($href, -20);
				while ($xmlElement->hasChildNodes()) 
				{
					$xmlElement->removeChild($xmlElement->firstChild);
		}
				$xmlElement->appendChild($xmlElement->ownerDocument->createTextNode($shortUrl));
			}
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
	
	/**
	 * @return string
	 */
	protected function getOpenTag()
	{
		return '[url="@Url@"]';
	}
	
	/**
	 * @return array
	 */
	public function getInfosForJS()
	{
		$infos = f_util_ArrayUtils::firstElement(parent::getInfosForJS());
		$infos['paramUrl'] = '${trans:m.website.bbeditor.param-url,ucf}';
		return array($infos);
	}
}

class website_BBCodeTagInfoDoc extends website_BBCodeTagInfo
{
	public function __construct()
	{
		parent::__construct('doc', 'a', 'document-link');
	}
	
	/**
	 * @param DOMElement $xmlElement
	 */
	public function normalizeXml($xmlElement)
	{
		$id = $this->getTagAttribute($xmlElement);
		if ($id === null)
		{
			$id = $xmlElement->textContent;
			while ($xmlElement->hasChildNodes()) {$xmlElement->removeChild($xmlElement->firstChild);}
		}
		$xmlElement->setAttribute('data-bbcode-attr', intval($id));
		$xmlElement->setAttribute('href', '#');
	}

	/**
	 * @param DOMElement $xmlElement
	 * @param DOMElement $xhtmlParent
	 * @return DOMElement Xhtml parent for content
	 */	
	public function toHtml($xmlElement, $xhtmlParent)
	{
		$documentId = $this->getTagAttribute($xmlElement);
		try
		{
			$document = DocumentHelper::getDocumentInstance($documentId);
		}
		catch (Exception $e)
		{
			$document = null;
		}
		
		if ($document !== null)
		{
			$element = $xhtmlParent->ownerDocument->createElement($this->getXmlNodeName());
			$element->setAttribute('href', LinkHelper::getDocumentUrl($document));
			$element->setAttribute('class', 'link');
			$xhtmlParent->appendChild($element);
			if (!$xmlElement->hasChildNodes())
			{
				$label = $element->ownerDocument->createTextNode($document->getLabelAsHtml());
				$element->appendChild($label);
			}
			return $element;
		}
		else 
		{
			$element = $xhtmlParent->ownerDocument->createElement('span');
			$element->setAttribute('class', 'error');
			$xhtmlParent->appendChild($element);
			$msg = $element->ownerDocument->createTextNode(LocaleService::getInstance()->transFO('m.website.bbeditor.invalid-documentid', array('ucf'), array('id' => $documentId)));
			$element->appendChild($msg);
			return $xhtmlParent;
		}
	}
	
	/**
	 * @param DOMElement $xmlElement
	 * @param string $beginStr
	 * @param string $endStr
	 */
	public function toBBCode($xmlElement, &$beginStr, &$endStr)
	{
		$id = $this->getTagAttribute($xmlElement);
		if (!$xmlElement->hasChildNodes())
		{
			$beginStr = '[' . $this->tagName . '="' . $id . '"/]';
			$endStr = '';
		}
		else
		{
			$beginStr = '[' . $this->tagName . '="' . $id . '"]';
			$endStr = '[/' . $this->tagName . ']';
		}
	}
	
	/**
	 * @return string
	 */
	protected function getOpenTag()
	{
		return '[doc="@Docid@"]';
	}
	
	/**
	 * @return array
	 */
	public function getInfosForJS()
	{
		$infos = f_util_ArrayUtils::firstElement(parent::getInfosForJS());
		$infos['paramDocid'] = '${trans:m.website.bbeditor.param-docid,ucf}';
		return array($infos);
	}
}

class website_BBCodeTagInfoAlign extends website_BBCodeTagInfo
{
	public function __construct()
	{
		parent::__construct('align', 'div', 'richtext/link');
	}

	/**
	 * @param DOMElement $xmlElement
	 */
	public function normalizeXml($xmlElement)
	{
		$align = $this->getTagAttribute($xmlElement);
		if ($align === null || !in_array($align, array('left','right','center','justify')))
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
		$elem->setAttribute('class', 'text-align-' . $this->getTagAttribute($xmlElement));
		return $elem;
	}

	/**
	 * @return array
	 */
	public function getInfosForJS()
	{
		return array(
			array(
				'label' => '${trans:m.website.bbeditor.align-left,ucf}',
				'icon' => 'richtext/align-left',
				'openTag' => '[align="left"]',
				'closeTag' => $this->getCloseTag(),
				'className' => $this->getClassName()
			),
			array(
				'label' => '${trans:m.website.bbeditor.align-justify,ucf}',
				'icon' => 'richtext/align-justify',
				'openTag' => '[align="justify"]',
				'closeTag' => $this->getCloseTag(),
				'className' => $this->getClassName()
			),
			array(
				'label' => '${trans:m.website.bbeditor.align-center,ucf}',
				'icon' => 'richtext/align-center',
				'openTag' => '[align="center"]',
				'closeTag' => $this->getCloseTag(),
				'className' => $this->getClassName()
			),
			array(
				'label' => '${trans:m.website.bbeditor.align-right,ucf}',
				'icon' => 'richtext/align-right',
				'openTag' => '[align="right"]',
				'closeTag' => $this->getCloseTag(),
				'className' => $this->getClassName()
			),
		);
	}
}

class website_BBCodeTagInfoQuote extends website_BBCodeTagInfo
{
	public function __construct()
	{
		parent::__construct('quote', 'blockquote', 'richtext/quote');
	}
	
	/**
	 * @param DOMElement $xmlElement
	 * @param DOMElement $xhtmlParent
	 * @return DOMElement Xhtml parent for content
	 */
	public function toHtml($xmlElement, $xhtmlParent)
	{
		$element = $xhtmlParent->ownerDocument->createElement($this->getXmlNodeName());
		$author = $this->getTagAttribute($xmlElement);
		if ($author)
		{
			$authorNode = $element->appendChild($xhtmlParent->ownerDocument->createElement('strong'));
			$authorNode->setAttribute('class', 'author');
			$authorNode->appendChild($xhtmlParent->ownerDocument->createTextNode($author . ' ' . LocaleService::getInstance()->transFO('m.website.bbeditor.someone-said', array('lab'))));
			$authorNode->appendChild($xhtmlParent->ownerDocument->createElement('br'));
		}
		$xhtmlParent->appendChild($element);
		if ($this->emptyTag)
		{
			return null;
		}
		return $element;
	}
	
	/**
	 * @return string
	 */
	protected function getOpenTag()
	{
		return '[quote="@Author@"]';
	}
	
	/**
	 * @return array
	 */
	public function getInfosForJS()
	{
		$infos = f_util_ArrayUtils::firstElement(parent::getInfosForJS());
		$infos['paramAuthor'] = '${trans:m.website.bbeditor.param-author,ucf}';
		return array($infos);
	}
}

class website_BBCodeTagInfoAbbr extends website_BBCodeTagInfo
{
	public function __construct()
	{
		parent::__construct('abbr', 'abbr', 'richtext/abbreviation');
	}
	
	/**
	 * @param DOMElement $xmlElement
	 * @param DOMElement $xhtmlParent
	 * @return DOMElement Xhtml parent for content
	 */
	public function toHtml($xmlElement, $xhtmlParent)
	{
		$element = $xhtmlParent->ownerDocument->createElement($this->getXmlNodeName());
		$definition = $this->getTagAttribute($xmlElement);
		if ($definition)
		{
			$element->setAttribute('title', $definition);
		}
		$xhtmlParent->appendChild($element);
		return $element;
	}
	
	/**
	 * @return string
	 */
	protected function getOpenTag()
	{
		return '[abbr="@Definition@"]';
	}
	
	/**
	 * @return array
	 */
	public function getInfosForJS()
	{
		$infos = f_util_ArrayUtils::firstElement(parent::getInfosForJS());
		$infos['paramDefinition'] = '${trans:m.website.bbeditor.param-definition,ucf}';
		return array($infos);
	}
}

class website_BBCodeTagInfoColor extends website_BBCodeTagInfo
{
	public function __construct()
	{
		parent::__construct('color', 'span', 'color');
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
	
	/**
	 * @return string
	 */
	protected function getOpenTag()
	{
		return '[color="@Color@"]';
	}
	
	/**
	 * @return array
	 */
	public function getInfosForJS()
	{
		$infos = f_util_ArrayUtils::firstElement(parent::getInfosForJS());
		$infos['paramColor'] = '${trans:m.website.bbeditor.param-color,ucf}';
		return array($infos);
	}
}

class website_BBCodeTagInfoList extends website_BBCodeTagInfo
{
	public function __construct()
	{
		parent::__construct('list', 'ul', 'richtext/unordered-list');
	}
	
	/**
	 * @param DOMElement $xmlElement
	 * @param DOMElement $xhtmlParent
	 * @return DOMElement Xhtml parent for content
	 */
	public function toHtml($xmlElement, $xhtmlParent)
	{
		$elem = parent::toHtml($xmlElement, $xhtmlParent);
		$elem->setAttribute('class', 'normal');
		return $elem;
	}
}

class website_BBCodeTagInfoListItem extends website_BBCodeTagInfo
{
	public function __construct()
	{
		parent::__construct('item', 'li', 'richtext/list-item');
	}
	
	/**
	 * @param DOMElement $xmlElement
	 * @param DOMElement $xhtmlParent
	 * @return DOMElement Xhtml parent for content
	 */
	public function toHtml($xmlElement, $xhtmlParent)
	{
		$elem = parent::toHtml($xmlElement, $xhtmlParent);
		$elem->setAttribute('class', 'normal');
		return $elem;
	}
}

class website_BBCodeEditor extends BaseService
{
	/**
	 * @var website_BBCodeEditor
	 */
	protected static $instance;
	
	/**
	 * @return website_BBCodeEditor
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = self::getServiceClassInstance(get_class());
		}
		return self::$instance;
	}
	
	/**
	 * @var boolean
	 */
	protected $bbcodeScriptAdded;
	
	/**
	 * @param array[] $params
	 * @param website_Page $context
	 * @return String
	 */
	public function buildEditor($params, $context)
	{
		// Add the jTagEditor class.
		$params['class'] = 'BBCodeEditor' . ((isset($params['class'])) ? (' ' . $params['class']) : '');
		
		if (isset($params['profile']) && $params['profile'])
		{
			$params['data-profile'] = $params['profile'];
		}
		else if (isset($params['module-profile']) && $params['module-profile'])
		{
			$parser = new website_BBCodeParser();
			$params['data-profile'] = $parser->getModuleProfile($params['module-profile']);
		}
		else 
		{
			$params['data-profile'] = 'default';
		}
		
		// Include the jTagEditor script.
		if (!$this->bbcodeScriptAdded)
		{
			$context->addScript('modules.website.lib.bbcode.BBCodeEditor');
			$context->addStyle('modules.website.BBCodeEditor');
			$this->bbcodeScriptAdded = true;
		}
		return website_FormHelper::renderTextarea($params);		
	}
	
	/**
	 * @return string
	 */
	public function compile()
	{
		$profiles = Framework::getConfigurationValue('modules/website/bbcodeProfiles', array('default'));
		$parser = new website_BBCodeParser();
		$infos = array();
		foreach ($profiles as $profile)
		{
			$parser->setProfile($profile);
			$profileInfos = array();
			foreach ($parser->getTagInfos() as $tagInfo)
			{
				$tagInfos = $tagInfo->getInfosForJS();
				if (is_array($tagInfos))
				{
					$profileInfos = array_merge($profileInfos, $tagInfos);
				}
			}
			$infos[$profile] = $profileInfos;
		}
		return JsonService::getInstance()->encode($infos);
	}
}