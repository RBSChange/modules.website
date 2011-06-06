<?php
class website_BlockStaticrichtextAction extends website_BlockAction
{
	/**
	 * @see website_BlockAction::execute()
	 *
	 * @param website_BlockActionRequest $request
	 * @param website_BlockActionResponse $response
	 * @return String
	 */
	function execute($request, $response)
	{
		// TODO: cache for a given time & dependent on page
		$content = $this->getConfigurationParameter('content');
		if ($this->isInBackoffice())
		{
			$content = preg_replace_callback('/<img\s+(.*?)\/>/i', array($this, "parseImageTagsBO"), $content);
			$dom = new DOMDocument("1.0", "UTF-8");
			$elem = $dom->createElement('richtextcontent');
			$elem->appendChild($dom->createCDATASection($content));
			$elem->setAttribute('style', 'display:none');
			$dom->appendChild($elem);
			$response->write($dom->saveXML($elem));
		}
		else if ($content)
		{
			$response->write(f_util_HtmlUtils::renderHtmlFragment($content));
		}
		return null;
	}
	
	private function parseImageTagsBO($imgMatch)
	{
		$matches = array();
		$attrs = array();
		if (preg_match_all('/\s*([\w:]*)\s*=\s*"(.*?)"/i', $imgMatch[1], $matches, PREG_SET_ORDER))
		{
			foreach ($matches as $match)
			{
				$attrs[strtolower($match[1])] = isset($match[3]) ? $match[3] : $match[2];
			}
		}
		
		if (isset($attrs["cmpref"]))
		{	
			$rc = RequestContext::getInstance();
			$lang = (isset($attrs["lang"]) ? $attrs["lang"] : $rc->getLang());
			$media = DocumentHelper::getDocumentInstance($attrs["cmpref"]);
			$formatInfo = $attrs;
			if (isset($attrs['format']) && !empty($attrs['format']))
	        {
	            list($stylesheet, $formatName) = explode('/', $attrs['format']);
				$formatInfo = MediaHelper::getFormatProperties($stylesheet, $formatName);
	        }
	        if (!$media->isLangAvailable($lang) || $media->getFilenameForLang($lang) === null)
	        {
	        	$lang = $media->getLang();
	        }
			$attrs["src"] = LinkHelper::getDocumentUrl($media, $lang, $formatInfo);
			$imgStr = "<img";
			foreach ($attrs as $key => $value)
			{
				$imgStr .= " ".$key."=\"".$value."\"";	
			}
			return $imgStr . " />";
		}
		return "<img " . $matches[1] . "/>";
	}
}
