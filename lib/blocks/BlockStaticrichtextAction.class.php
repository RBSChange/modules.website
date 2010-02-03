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
}