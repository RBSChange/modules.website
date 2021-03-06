<?php
/**
 * @deprecated (will be removed in 4.0) use website_BBCodeParser
 */
class website_BBCodeService extends BaseService
{
	/**
	 * @deprecated (will be removed in 4.0)
	 */
	const URL_STRING_REGEXP = '(?:http\:\/\/|https\:\/\/|ftp\:\/\/)[a-zA-Z0-9,;\:\/\-\+\?%\&\.\=\_\~\#\\\'\[\]\{\}]+';

	/**
	 * @deprecated (will be removed in 4.0)
	 */
	protected static $instance;
	
	/**
	 * @deprecated (will be removed in 4.0)
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
	 * @deprecated (will be removed in 4.0)
	 */
	protected $codeContents = array();
	
	/**
	 * @deprecated (will be removed in 4.0)
	 */
	protected $bbcodeScriptAdded = false;
	
	/**
	 * @deprecated (will be removed in 4.0)
	 */
	public function buildEditor($params, $context)
	{
		// Add the jTagEditor class.
		$params['class'] = 'jTagEditor' . ((isset($params['class'])) ? $params['class'] : '');
		
		// Include the jTagEditor script.
		if (!$this->bbcodeScriptAdded)
		{
			$context->addScript('modules.website.lib.bbeditor.jtageditor');
			$context->addStyle('modules.website.jtageditor');
			$this->bbcodeScriptAdded = true;
		}
		return website_FormHelper::renderTextarea($params);		
	}	
		
	/**
	 * @deprecated (will be removed in 4.0)
	 */
	public function parseCode($matches)
	{
		$this->codeContents[] = $matches[1];
		return '[code='.(count($this->codeContents)-1).']';
	}
	
	/**
	 * @deprecated (will be removed in 4.0)
	 */
	public function fixContent($bbcode)
	{
		if (f_util_StringUtils::isEmpty($bbcode))
		{
			return null;
		}
		
		// Extract all code tags.
		$bbcode = $this->extractCodeContentFix($bbcode);
		
		// Handle default bbcodes.
		$bbcode = $this->fixDefaultCodes($bbcode);		
			
		// Handle specific bbcodes.
		$bbcode = $this->fixSpecificCodes($bbcode);		
		
		// Re-integrate code tags.
		$bbcode = $this->reinjectCodeContentFix($bbcode);
		
		return $bbcode;
	}
	
	/**
	 * @deprecated (will be removed in 4.0)
	 */
	protected function extractCodeContentFix($bbcode)
	{
		return $this->extractCodeContent($bbcode);
	}
	
	/**
	 * @deprecated (will be removed in 4.0)
	 */
	protected function fixDefaultCodes($bbcode)
	{
		// -- Fix URL tags.
		
		// Add quotation marks around URLs.
		$bbcode = preg_replace_callback('/\[(url)=('.self::URL_STRING_REGEXP.'?)\](.*)\[\/(url)\]/is', array($this, 'addQuotesAroundUrl'), $bbcode);
				
		// Fix URL tags.
		$bbcode = preg_replace_callback('/\[url\]('.self::URL_STRING_REGEXP.'?)\[\/url\]/is', array($this, 'shortenUrl'), $bbcode);
		
		// Add URL tag over URLs.
		$bbcode = preg_replace_callback('/(?<=^|<br \/>|\s)('.self::URL_STRING_REGEXP.')(?=<br \/>|\s|$)/is', array($this, 'shortenUrl'), $bbcode);

		// -- Fix IMG tags.
		
		// Add quotation marks around URLs.
		$bbcode = preg_replace_callback('/\[(img)=('.self::URL_STRING_REGEXP.'?)\](.*)\[\/(img)\]/is', array($this, 'addQuotesAroundUrl'), $bbcode);
		
		// Fix URL tags.
		$bbcode = preg_replace('/\[img\]('.self::URL_STRING_REGEXP.'?)\[\/img\]/is', '[img="$1"][/img]', $bbcode);
		
		return $bbcode;
	}
	
	/**
	 * @deprecated (will be removed in 4.0)
	 */
	protected function fixSpecificCodes($bbcode)
	{
		// Overload this method to fix specific bbcodes.		
		return $bbcode;
	}
	
	/**
	 * @deprecated (will be removed in 4.0)
	 */
	protected function reinjectCodeContentFix($html)
	{
		if (count($this->codeContents))
		{
			foreach ($this->codeContents as $index => $content)
			{
				$html = str_replace('[code=' . $index . ']', '[code]'.$content.'[/code]', $html);
			}
			$this->codeContents = array();
		}
		return $html;
	}
	
	/**
	 * @deprecated (will be removed in 4.0)
	 */
	public function toHtml($bbcode)
	{
		if (f_util_StringUtils::isEmpty($bbcode))
		{
			return null;
		}
		
		// Extract all code tags.
		$html = $this->extractCodeContent($bbcode);
		
		// Replace any html brackets with HTML Entities to prevent executing HTML or script
		// Don't use strip_tags here because it breaks [url] search by replacing & with amp
		$html = f_util_HtmlUtils::textToHtml($html);
		
		// Convert default BBCodes.
		$html = $this->convertDefaultCodes($html);
		
		// Handle specific bbcodes.
		$html = $this->convertSpecificCodes($html);
		
		// Re-integrate code tags.
		$html = $this->reinjectCodeContent($html);
		
		return $html;
	}
	
	/**
	 * @deprecated (will be removed in 4.0)
	 */
	protected function extractCodeContent($bbcode)
	{
		return preg_replace_callback('(\[code\]((?:[^[]|\[(?!/?code])|(?R))+)\[\/code\])is', array($this, 'parseCode'), $bbcode);
	}
	
	/**
	 * @deprecated (will be removed in 4.0)
	 */
	protected function convertDefaultCodes($html)
	{
		$html = $this->parseQuote($html);
		
		$pattern = array();
		$replacement = array();
				
		// Check for bold text
		$pattern[] = '/\[b\](.+?)\[\/b]/is';
		$replacement[] = '<strong>$1</strong>';
		
		// Check for Italics text
		$pattern[] = '/\[i\](.+?)\[\/i\]/is';
		$replacement[] = '<em>$1</em>';

		// Check for Underline text
		$pattern[] = '/\[u\](.+?)\[\/u\]/is';
		$replacement[] = '<span style="text-decoration: underline;">$1</span>';		
		
		// Check for strike-through text
		$pattern[] = '/\[s\](.+?)\[\/s\]/is';
		$replacement[] = '<del>$1</del>';

		// Images
		$pattern[] = '/\[img=\&quot\;('.self::URL_STRING_REGEXP.')\&quot\;\](.*?)\[\/img\]/is';
		$replacement[] = '<img src="$1" alt="$2" title="$2" />';
		
		// Perform URL Search
		$pattern[] = '/\[url=\&quot\;('.self::URL_STRING_REGEXP.')\&quot\;\](.+?)\[\/url\]/is';
		$replacement[] = '<a class="link" href="$1" target="_blank">$2</a>';
		
		// Alignments.
		$pattern[] = '/\[align=(left|right|center|justify)\](.+?)\[\/align\]/si';
		$replacement[] = '<div class="align-$1">$2</div>';
		
		return preg_replace($pattern, $replacement, $html);
	}
	
	/**
	 * @deprecated (will be removed in 4.0)
	 */
	public function parseQuote($html)
	{
		$pattern = '/\[quote(?:\=([^\]]*))?]((?:[^[]|\[(?!\/?quote(?:\=[^\]]*)?])|(?R))+)\[\/quote\]/is';
		return preg_replace_callback($pattern, array($this, 'parseQuoteCallback'), $html);
	}
	
	/**
	 * @deprecated (will be removed in 4.0)
	 */
	public function parseQuoteCallback($matches)
	{
		if (!$matches[1])
		{
			return '<blockquote>' . $this->parseQuote($matches[2]) . '</blockquote>';
		}
		else 
		{
			$quoteLabel = f_Locale::translate('&modules.website.bbeditor.someone-saidLabel;');
			return '<blockquote cite="' . $matches[1] . '"><strong class="author">' . $matches[1] . ' '.$quoteLabel.'</strong><br />' . $this->parseQuote($matches[2]) . '</blockquote>';
		}	
	}
	
	/**
	 * @deprecated (will be removed in 4.0)
	 */
	protected function reinjectCodeContent($html)
	{
		if (count($this->codeContents))
		{
			foreach ($this->codeContents as $index => $content)
			{
				$html = str_replace('[code=' . $index . ']', '<pre class="code">'.htmlspecialchars($content, ENT_COMPAT, "utf-8").'</pre>', $html);
			}
			$this->codeContents = array();
		}
		return $html;
	}
	
	/**
	 * @deprecated (will be removed in 4.0)
	 */
	protected function convertSpecificCodes($html)
	{
		// Overload this method to handle specific bbcodes conversion.		
		return $html;
	}
		
	/**
	 * @deprecated (will be removed in 4.0)
	 */
	public function toText($bbcode)
	{
		if (f_util_StringUtils::isEmpty($bbcode))
		{
			return null;
		}
		
		// Replace any html brackets with HTML Entities to prevent executing HTML or script
		// Don't use strip_tags here because it breaks [url] search by replacing & with amp
		$text = htmlspecialchars($bbcode);
		
		// Handle default bbcodes.
		$text = $this->removeDefaultCodes($text);
		
		// Handle specific bbcodes.
		$text = $this->removeSpecificCodes($text);
		
		return $text;
	}
	
	/**
	 * @deprecated (will be removed in 4.0)
	 */
	protected function removeDefaultCodes($text)
	{
		$pattern = array();
		$replacement = array();
				
		// Check for bold text
		$pattern[] = '/\[b\](.+?)\[\/b]/is';
		$replacement[] = '$1';
		
		// Check for Italics text
		$pattern[] = '/\[i\](.+?)\[\/i\]/is';
		$replacement[] = '$1';

		// Check for Underline text
		$pattern[] = '/\[u\](.+?)\[\/u\]/is';
		$replacement[] = '$1';		
		
		// Check for strike-through text
		$pattern[] = '/\[s\](.+?)\[\/s\]/is';
		$replacement[] = '$1';
		
		// [quote]quoted text[/quote]
		$pattern[] = '/\[quote\](.+?)\[\/quote\]/is';
		$replacement[] = '$1';	

		$pattern[] = '/\[quote\=([^\]]*)\](.+?)\[\/quote\]/is';
		$replacement[] = '$2';	
		
		// Images
		$pattern[] = '/\[img=\&quot\;('.self::URL_STRING_REGEXP.')\&quot\;\](.*?)\[\/img\]/is';
		$replacement[] = ' $2 ';		

		// Perform URL Search
		$pattern[] = '/\[url=\&quot\;('.self::URL_STRING_REGEXP.')\&quot\;\](.+?)\[\/url\]/is';
		$replacement[] = '$2';
		
		// [code]code text[/code]
		$pattern[] = '/\[code\](.+?)\[\/code\]/is';
		$replacement[] = '$1';	
		
		// Alignments.
		$pattern[] = '/\[align=(left|right|center|justify)\](.+?)\[\/align\]/si';
		$replacement[] = '$2';
		
		return preg_replace($pattern, $replacement, $text);
	}
	
	/**
	 * @deprecated (will be removed in 4.0)
	 */
	protected function removeSpecificCodes($text)
	{
		// Overload this method to handle specific bbcodes removal.		
		return $text;
	}
	
	/**
	 * @deprecated (will be removed in 4.0)
	 */
	public function shortenUrl($matches)
	{
		$shortUrl = $matches[1];
		if (f_util_StringUtils::strlen($shortUrl) > 50)
		{
			$shortUrl = f_util_StringUtils::substr($shortUrl, 0, 20) . '.....' . f_util_StringUtils::substr($shortUrl, -20);
		}
		return '[url="' . $matches[1] . '"]' . $shortUrl . '[/url]';
	}
	
	/**
	 * @deprecated (will be removed in 4.0)
	 */
	public function addQuotesAroundUrl($matches)
	{
		$stringToFix = $matches[2].']'.$matches[3];
		$fixedString = '';
		$openBrackets = 0;
		$lenght = f_util_StringUtils::strlen($stringToFix);
		for ($i = 0 ; $i < $lenght ; $i++)
		{
			$char = f_util_StringUtils::substr($stringToFix, $i, 1);
			if ($char == '[')
			{
				$openBrackets++;
			}
			else if ($char == ']')
			{
				if ($openBrackets > 0)
				{
					$openBrackets--;
				}
				// It is the URL tag closing bracket.
				else 
				{
					$fixedString .= '"';
					$fixedString .= f_util_StringUtils::substr($stringToFix, $i);
					break;
				}
			}
			$fixedString .= $char;
		}
		return '['.$matches[1].'="' . $fixedString . '[/'.$matches[4].']';
	}
	
	/**
	 * @deprecated (will be removed in 4.0)
	 */
	public function addJs($page)
	{
		foreach ($this->getDefaultJs() as $script)
		{
			$page->addScript($script);
		}
		foreach ($this->getSpecificJs() as $script)
		{
			$page->addScript($script);
		}
	}
	
	/**
	 * @deprecated (will be removed in 4.0)
	 */
	public function getJs()
	{
		return array_merge($this->getDefaultJs(), $this->getSpecificJs());
	}
	
	/**
	 * @deprecated (will be removed in 4.0)
	 */
	protected function getDefaultJs()
	{
		return array();
	}
	
	/**
	 * @deprecated (will be removed in 4.0)
	 */
	protected function getSpecificJs()
	{
		// Overload this method to handle specific bbcodes removal.
		return array();
	}
	
	/**
	 * @deprecated (will be removed in 4.0)
	 */
	public function removeBBCode($bbcode)
	{
		return $this->toText($bbcode);
	}
}