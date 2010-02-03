<?php
class website_BBCodeService extends BaseService
{
	/**
	 * @var website_BBCodeService.
	 */
	protected static $instance;
	
	/**
	 * @return website_ListStylesheetsService
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
	 * @var String[]
	 */
	private $codeContents = array();
	
	/**
	 * @param Array $matches
	 * @return String
	 */
	public function parseCode($matches)
	{
		$this->codeContents[] = $matches[1];
		return '[code='.(count($this->codeContents)-1).']';
	}
	
	/**
	 * @param String $bbcode
	 * @return String
	 */
	public function toHtml($bbcode)
	{
		if (empty($bbcode))
		{
			return null;
		}
		
		// Extract all code tags.
		$html = preg_replace_callback('(\[code\](.+?)\[\/code\])is', array($this, 'parseCode'), $bbcode);
		
		// Replace any html brackets with HTML Entities to prevent executing HTML or script
		// Don't use strip_tags here because it breaks [url] search by replacing & with amp
		$html = f_util_HtmlUtils::textToHtml($html);
		
		
		$pattern = array();
		$replacement = array();
				
		// Check for bold text
		$pattern[] = "(\[b\](.+?)\[\/b])is";
		$replacement[] = '<b>$1</b>';
		
		// Check for Italics text
		$pattern[] = "(\[i\](.+?)\[\/i\])is";
		$replacement[] = '<i>$1</i>';

		// Check for Underline text
		$pattern[] = "(\[u\](.+?)\[\/u\])is";
		$replacement[] = '<span style="text-decoration: underline;">$1</span>';		
		
		// Check for strike-through text
		$pattern[] = "(\[s\](.+?)\[\/s\])is";
		$replacement[] = '<s>$1</s>';
		
		//[quote]quoted text[/quote]
		$pattern[] = "(\[quote\](.+?)\[\/quote\])is";
		$replacement[] = '<blockquote>$1</blockquote>';	

		$pattern[] = "(\[quote\=([^\]]*)\](.+?)\[\/quote\])is";
		$replacement[] = '<blockquote cite="$1">$2</blockquote>';	
		
		// Images
		$pattern[] = "/\[img\](.+?)\[\/img\]/";
		$replacement[] = '<img src="$1" />';
		
		// Perform URL Search
		$URLSearchString = " a-zA-Z0-9,;\:\/\-\?\&\.\=\_\~\#\'";
		$pattern[] = "/\[url\]([$URLSearchString]+)\[\/url\]/";
		$replacement[] = '<a href="$1" target="_blank">$1</a>';
		
		$pattern[] = "(\[url\=([$URLSearchString]+)\](.+?)\[\/url\])";
		$replacement[] = '<a href="$1" target="_blank">$2</a>';
		
		$html = preg_replace($pattern, $replacement, $html);
		
		// Re-integrate code tags.
		if (count($this->codeContents))
		{
			foreach ($this->codeContents as $index => $content)
			{
				$html = str_replace('[code=' . $index . ']', '<pre>'.$content.'</pre>', $html);
			}
			$this->codeContents = array();
		}
		
		return $html;
	}
	
	/**
	 * @param String $bbcode
	 * @return String
	 */
	public function removeBBCode($bbcode)
	{
		if (empty($bbcode))
		{
			return null;
		}
		
		// Replace any html brackets with HTML Entities to prevent executing HTML or script
		// Don't use strip_tags here because it breaks [url] search by replacing & with amp
		$html = htmlspecialchars($bbcode);
		
		$pattern = array();
		$replacement = array();
				
		// Check for bold text
		$pattern[] = "(\[b\](.+?)\[\/b])is";
		$replacement[] = '$1';
		
		// Check for Italics text
		$pattern[] = "(\[i\](.+?)\[\/i\])is";
		$replacement[] = '$1';

		// Check for Underline text
		$pattern[] = "(\[u\](.+?)\[\/u\])is";
		$replacement[] = '$1';		
		
		// Check for strike-through text
		$pattern[] = "(\[s\](.+?)\[\/s\])is";
		$replacement[] = '$1';
		
		//[quote]quoted text[/quote]
		$pattern[] = "(\[quote\](.+?)\[\/quote\])is";
		$replacement[] = '$1';	

		$pattern[] = "(\[quote\=([^\]]*)\](.+?)\[\/quote\])is";
		$replacement[] = '$2';	
		
		// Images
		$pattern[] = "/\[img\](.+?)\[\/img\]/";
		$replacement[] = '';		
		
		// Perform URL Search
		$URLSearchString = " a-zA-Z0-9\:\/\-\?\&\.\=\_\~\#\'";
		$pattern[] = "/\[url\]([$URLSearchString]*)\[\/url\]/";
		$replacement[] = '$1';
		
		$pattern[] = "(\[url\=([$URLSearchString]*)\](.+?)\[/url\])";
		$replacement[] = '$2';	
		
		//[code]code text[/code]
		$pattern[] = "(\[code\](.+?)\[\/code\])is";
		$replacement[] = '$1';	
		
		$html = preg_replace($pattern, $replacement, $html);
		return $html;
	}	
}