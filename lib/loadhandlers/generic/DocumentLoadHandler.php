<?php
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
			$request->setAttribute($attrName, $this->getDocumentParameter($paramName));
		}
	}
}