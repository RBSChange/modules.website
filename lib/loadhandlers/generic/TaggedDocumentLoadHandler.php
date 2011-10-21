<?php
class generic_TaggedDocumentLoadHandler extends website_ViewLoadHandlerImpl
{
	/**
	 * @param f_mvc_Request $request
	 * @param f_mvc_Response $response
	 */
	function execute($request, $response)
	{
		$attrName = $this->getParameter(0, 'taggedDocument');
		
		if (!$request->hasAttribute($attrName))
		{
			$tagName = $this->getParameter(1);
			if ($tagName)
			{
				$ts = TagService::getInstance();
				try 
				{
					$document = null;
					if ($ts->isContextualTag($tagName))
					{
						$document = $ts->getDocumentByContextualTag($tagName, website_WebsiteService::getInstance()->getCurrentWebsite());
					}
					else if ($ts->isFunctionalTag($tagName))
					{
						$document = $ts->getDocumentBySiblingTag($tagName, website_PageService::getInstance()->getCurrentPage());
					}
					else if ($ts->isExclusiveTag($tagName))
					{
						$document = $ts->getDocumentByExclusiveTag($tagName);
					}
					
					if ($document !== null)
					{
						$request->setAttribute($attrName, $document);
					}
				}
				catch (Exception $e)
				{
					// Do nothing.
					if (Framework::isDebugEnabled())
					{
						Framework::exception($e);
					}	
				}
			}
		}
	}
}