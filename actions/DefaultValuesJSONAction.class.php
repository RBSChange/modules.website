<?php
class website_DefaultValuesJSONAction extends generic_DefaultValuesJSONAction
{
	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	public function _execute($context, $request)
	{
		$modelName = $request->getParameter('modelname');
		if ($modelName !== 'modules_website/pageversion')
		{
			return parent::_execute($context, $request);
		}
		$document = $this->getDocumentInstanceFromRequest($request);
		if (!$document instanceof website_persistentdocument_page ) 
		{
			throw new Exception('Not valid type (page) for parent node: ' . get_class($document));
		}		
	
		if (!$request->hasParameter('duplicate'))
		{
			return parent::_execute($context, $request);
		}
		
		// For correction get master document.
		if ($document->getCorrectionofid())
		{
			$document = DocumentHelper::getDocumentInstance($document->getCorrectionofid());
		}
		
		$allowedProperties = explode(',', $request->getParameter('documentproperties', ''));
		$data = uixul_DocumentEditorService::getInstance()->exportFieldsData($document, $allowedProperties);
		
		unset($data['author']);
		return $this->sendJSON($data);
	}
}