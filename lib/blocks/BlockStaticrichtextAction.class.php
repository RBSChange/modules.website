<?php
class website_BlockStaticrichtextAction extends website_BlockAction
{
	/**
	 * @see website_BlockAction::execute()
	 *
	 * @param website_BlockActionRequest $request
	 * @param website_BlockActionResponse $response
	 * @return string
	 */
	function execute($request, $response)
	{
		// TODO: cache for a given time & dependent on page
		$content = $this->getConfigurationParameter('content');
		if ($this->isInBackofficeEdition())
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
		$formatInfo = array();
		if (preg_match_all('/\s*([\w:]*)\s*=\s*"(.*?)"/i', $imgMatch[1], $matches, PREG_SET_ORDER))
		{
			$formatAttributes = array('max-height', 'max-width', 'min-height', 'min-width', 'height', 'width');
			foreach ($matches as $match)
			{
				$key = strtolower($match[1]);
				$attrs[$key] = isset($match[3]) ? $match[3] : $match[2];
				if (in_array($key, $formatAttributes))
				{
					$formatInfo[$key] = $attrs[$key];
				}
			}
		}
		
		if (isset($attrs["cmpref"]))
		{
			try
			{
				$rc = RequestContext::getInstance();
				$lang = (isset($attrs["lang"]) ? $attrs["lang"] : $rc->getLang());
				$media = DocumentHelper::getDocumentInstance($attrs["cmpref"]);
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
			}
			catch (Exception $e)
			{
				Framework::exception($e);
				$attrs["class"] = 'image-broken';
				$attrs["src"] = MediaHelper::getIcon('unknown', 'normal');
				$alt = LocaleService::getInstance()->trans('m.media.frontoffice.broken-image', array('ucf'));
				$attrs["title"] = $alt;
				$attrs["alt"] = $alt;
			}
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
