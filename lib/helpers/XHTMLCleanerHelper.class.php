<?php
class website_XHTMLCleanerHelper
{
	private static $xsltCleanerCachePath;

	/**
	 * @param String $XHTMLFragment
	 * @return String
	 */
	public static function clean($XHTMLFragment)
	{
		if (f_util_StringUtils::isEmpty($XHTMLFragment)) {return '';}
		$domTemplate = new DOMDocument('1.0', 'UTF-8');
		$domTemplate->substituteEntities = false;
		$domTemplate->resolveExternals = true;
		if (DIRECTORY_SEPARATOR !== '/')
		{
			$dtdPath = 'file:///' . str_replace(array(DIRECTORY_SEPARATOR, ' '), array('/', '%20'), realpath(WEBEDIT_HOME .'/framework/f_web/dtd/xhtml1-transitional.dtd'));
		}
		else
		{
			$dtdPath = 'file://' . str_replace(' ', '%20', realpath(WEBEDIT_HOME .'/framework/f_web/dtd/xhtml1-transitional.dtd'));
		}	
		
		$xml = '<?xml version="1.0" encoding="UTF-8"?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "'.$dtdPath.'"><body>' . $XHTMLFragment . '</body>';
		if (!$domTemplate->loadXML($xml))
		{
			throw new Exception("Could not load $xml as XML");
		}
		$xslt = self::getCleanerXSLTProcessor();
		$content = $xslt->transformToXml($domTemplate);
		
		$reg = array('/<a\s+([^>]*)\/>/i', '/<\/?body([^>]*)>\s*/i');
		$replace = array('<a $1></a>', '');
		$content = preg_replace($reg, $replace, $content);
		return $content;
	}

	/**
	 * @return XSLTProcessor
	 */
	private static function getCleanerXSLTProcessor()
	{
		if (self::$xsltCleanerCachePath === null)
		{
			self::$xsltCleanerCachePath = f_util_FileUtils::buildCachePath('cleanXHTMLFragment.xsl');
			if (!is_readable(self::$xsltCleanerCachePath))
			{
				self::generateCleanerXSLT();
			}
		}
		$xsl = new DOMDocument('1.0', 'UTF-8');
		$xsl->load(self::$xsltCleanerCachePath);
		$xslt = new XSLTProcessor();
		$xslt->registerPHPFunctions();
		$xslt->importStylesheet($xsl);
		return $xslt;
	}

	private static function generateCleanerXSLT()
	{
		$defaultXslPath = FileResolver::getInstance()->setPackageName('modules_website')->setDirectory('lib')->getPath('cleanXHTMLFragment.xsl');
		$xsl = new DOMDocument('1.0', 'UTF-8');
		$xsl->load($defaultXslPath);
		foreach (self::getProjectStyle() as $rule)
		{
			$tag = $rule['tag'];
			$class = $rule['class'];
				
			$template = $xsl->createElement('xsl:template');
			$template->setAttribute('match', $tag."[@class='$class']");
			$elem = $xsl->createElement($tag);
			$elem->setAttribute('class', $class);
			$elem->appendChild($xsl->createElement('xsl:apply-templates'));
			$template->appendChild($elem);
			$xsl->documentElement->appendChild($template);
				
			$template = $xsl->createElement('xsl:template');
			$template->setAttribute('match', $tag."[@class='$class' and normalize-space(.) ='' and not(descendant::*)]");
			$template->setAttribute('priority', "10");
			$xsl->documentElement->appendChild($template);
		}

		$xsl->save(self::$xsltCleanerCachePath);
	}

	private static function getProjectStyle()
	{
		$result = uixul_RichtextConfigService::getInstance()->getConfigurationArray();
		$rules = array();
		foreach ($result as $row)
		{
			if (isset($row[uixul_RichtextConfigService::ATTR_ATTRIBUTE_NAME]) && isset($row[uixul_RichtextConfigService::ATTR_ATTRIBUTE_NAME]['class']))
			{
				$rules[] = array('tag' => $row[uixul_RichtextConfigService::TAG_ATTRIBUTE_NAME], 'class' => $row[uixul_RichtextConfigService::ATTR_ATTRIBUTE_NAME]['class']);
			}
		}
		return $rules;
	}

	/**
	 * @param DOMElement $element
	 * @return string
	 */
	public static function safeSrc($elementArray)
	{
		$element = $elementArray[0];
		if ($element->hasAttribute('cmpref'))
		{
			$elementId = intval($element->getAttribute('cmpref'));
			try
			{
				$document = DocumentHelper::getDocumentInstance($elementId);
				if ($document instanceof media_persistentdocument_file)
				{
					$format = null;
					$lang = $element->hasAttribute('lang') ? $element->getAttribute('lang') : null;
					if ($element->hasAttribute('format'))
					{
						$format = MediaHelper::getFormatPropertiesByName($element->getAttribute('format'));
					}
					else if ($element->hasAttribute("width") && $element->hasAttribute("height"))
					{
						$format = array("width" => $element->getAttribute("width"),
										"height" => $element->getAttribute("height"));
					}
					$src = $document->getDocumentService()->generateAbsoluteUrl($document, $lang, $format);
				}
				else
				{
					throw new Exception('Invalid media document : ' . $elementId);
				}
			}
			catch (Exception $e)
			{
				Framework::exception($e);
				$src = "/changeicons/normal/unknown.png";
				$element->removeAttribute('cmpref');
				$alt = $element->getAttribute('alt');
				$element->setAttribute('alt', $alt . ' (Invalid media document # '. $elementId . ')');
			}
		}
		else
		{
			$src = $element->getAttribute('src');
			if (f_util_StringUtils::beginsWith($src, 'file:'))
		{
			$element->setAttribute('alt', $src);
			$src = "/changeicons/normal/environment_error.png";
			$element->removeAttribute('height');
			$element->removeAttribute('width');
			}
		}
		return $src;
	}

	/**
	 * @param DOMElement $element
	 * @return string
	 */
	public static function safeImgStyle($elementArray)
	{
		$element = $elementArray[0];
		$style = '';
		if ($element->hasAttribute('style'))
		{
			$styleArray = f_util_HtmlUtils::parseStyleAttributes($element->getAttribute('style'));
			if (isset($styleArray['vertical-align']))
			{
				$style = 'vertical-align:'.$styleArray['vertical-align'];
			}
			if (isset($styleArray['width']) && !$element->hasAttribute('width'))
			{
				$width = intval($styleArray['width']);
				if ($width > 0)
				{
					$element->setAttribute('width', $width);
				}
			}
			if (isset($styleArray['height']) && !$element->hasAttribute('height'))
			{
				$height = intval($styleArray['height']);
				if ($height > 0)
				{
					$element->setAttribute('height', $height);
				}
			}
		}
		return $style;
	}

	/**
	 * @param DOMElement $element
	 * @return string
	 */
	public static function safeImgClass($elementArray)
	{
		$element = $elementArray[0];
		$class = 'image';
		if ($element->hasAttribute('class'))
		{
			$classArray = explode(' ', $element->getAttribute('class'));
			if (in_array('float-left', $classArray))
			{
				$class .= ' float-left';
			}
			elseif (in_array('float-right', $classArray))
			{
				$class .= ' float-right';
			}
		}
		elseif ($element->hasAttribute("align"))
		{
			$align = $element->getAttribute("align");
			if ($align === "left")
			{
				$class .= ' float-left';
			}
			elseif ($align === "right")
			{
				$class .= ' float-right';
			}
		}
		return $class;
	}

	/**
	 * @param DOMElement $element
	 * @return string
	 */
	public static function safeAClass($elementArray)
	{
		$element = $elementArray[0];
		$class = 'link';
		$classArray = array();
		if ($element->hasAttribute('class'))
		{
			$classArray = explode(' ', $element->getAttribute('class'));
			if (in_array('document-dummy', $classArray))
			{
				return 'document-dummy';
			}
			else if (in_array('media-flash-dummy', $classArray))
			{
				return 'media-flash-dummy';
			}
			if (in_array('tooltip', $classArray))
			{
				$class .= ' tooltip';
			}

			if (in_array('popup', $classArray))
			{
				$class .= ' popup';
			}
		}
	
		// Deprecated attribute.
		if ($element->hasAttribute('popup'))
		{
			if (!in_array('popup', $classArray))
			{
				$class .= ' popup';
			}
			$element->removeAttribute('popup');
		}
		return $class;
	}
	
	public static function safeAHref($elementArray)
	{
		$element = $elementArray[0];
		$href = $element->hasAttribute('href') ? $element->getAttribute('href')  : '#';
		if (strpos($href, '/') === 0) {$href = '#';}
				
		// Deprecated attribute.
		if ($element->hasAttribute('cmpref'))
		{
			$cmpref = intval($element->getAttribute('cmpref'));
			if ($cmpref > 0)
			{
				if (!$element->hasAttribute('rel'))
				{
					$element->setAttribute('rel', 'cmpref:' . $element->getAttribute('cmpref'));
				}
				else
				{
					$ok = false;
					foreach (explode(',', $element->getAttribute('class')) as $rel) 
					{
						if (strpos($rel, 'cmpref:') === 0) {$ok = true;}
						break;
					}
					if (!$ok)
					{
						$element->setAttribute('rel', 'cmpref:' . $cmpref . ',' . $element->getAttribute('rel'));
					}
				}
				$href = '#';
			}
			$element->removeAttribute('cmpref');
		}
		else if ($element->hasAttribute('rel') && strpos($element->getAttribute('rel'), 'cmpref:') !== false)
		{
			$href = '#';
		}
		
		if ('document-dummy' == $element->getAttribute('class') || 'media-flash-dummy' == $element->getAttribute('class'))
		{
			$href = '#';
		}
		else if ($element->hasAttribute('style'))
		{
			$element->removeAttribute('style');
		}
		return $href;		
	}

	static private $defaultHClasses;

	/**
	 * @param DOMElement $element
	 * @return string
	 */
	public static function safeHClass($elementArray)
	{
		if (self::$defaultHClasses === null)
		{
			self::$defaultHClasses = array('h1' => 'heading-one', 'h2' => 'heading-two', 'h3' => 'heading-three',
			 'h4' => 'heading-four', 'h5' => 'heading-five', 'h6' => 'heading-six');
				
			foreach (array_reverse(self::getProjectStyle()) as $rule)
			{
				self::$defaultHClasses[$rule['tag']] = $rule['class'];
			}
		}

		$element = $elementArray[0];
		return self::$defaultHClasses[$element->nodeName];
	}
}
