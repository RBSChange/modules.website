<?php
/**
 * This loadHandler loads a document using a parameter value and defines a request attribute with it
 * For example: use mydocId parameter value to load an instance of mymodule_persistentdocument_mydoc and set is as "mydoc" attribute <tal:block change:loadhandler="generic_DocumentLoadHandler" args="mydoc, mydocId, mymodule_persistentdocument_mydoc" />
 */
class generic_DocumentLoadHandler extends website_ViewLoadHandlerImpl
{
	/**
	 * @param f_mvc_Request $request
	 * @param f_mvc_Response $response
	 */
	function execute($request, $response)
	{
		$attrName = $this->getParameter(0, 'document');
		
		if (!$request->hasAttribute($attrName))
		{
			$paramName = $this->getParameter(1, K::COMPONENT_ID_ACCESSOR);
			$expectedClassName = $this->getParameter(2);
			$request->setAttribute($attrName, $this->getDocumentParameter($paramName, $expectedClassName));
		}
	}
}