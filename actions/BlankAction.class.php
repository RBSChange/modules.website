<?php
/**
 * website_BlankAction
 * @package modules.website
 */
class website_BlankAction extends change_Action
{
	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	public function _execute($context, $request)
	{
		header("Expires: " . gmdate("D, d M Y H:i:s", time()+60) . " GMT");
		header("Cache-Control:");
		header("Pragma:");
		RequestContext::getInstance()->setCompleteUserAgent('xul');
		$ss = website_StyleService::getInstance();
		try
		{
			$documentId = $this->getDocumentIdFromRequest($request);
			if (is_numeric($documentId))
			{
				$document = DocumentHelper::getDocumentInstance($documentId);
				if ($document instanceof website_persistentdocument_page)
				{
					$ss->registerStyle('modules.generic.richtextbo');
					
					website_PageService::getInstance()->setCurrentPageId($document->getId());
					$prs = website_PageRessourceService::getInstance();
					$prs->setPage($document);
					
					$skinId = $document->getSkinId();	
					if ($skinId)
					{
						$prs->setSkin(DocumentHelper::getDocumentInstance($skinId));
					}
					$request->setAttribute('cssPageInclusion', $prs->getPageStylesheetInclusion());
				}
				else
				{
					$ss->registerStyle('modules.generic.frontoffice');
					$ss->registerStyle('modules.generic.richtextbo');
				}
			}
			else
			{
				$ss->registerStyle('modules.generic.frontoffice');
				$ss->registerStyle('modules.generic.richtextbo');
			}
		}
		catch (Exception $e)
		{
			Framework::exception($e);
		}
		
		if ($request->hasParameter('specificstylesheet'))
		{
			foreach (explode(',', $request->getParameter('specificstylesheet')) as $stylesheet)
			{
				$ss->registerStyle(trim($stylesheet));
			}
		}
		$request->setAttribute('cssInclusion', $ss->execute('html'));
		return change_View::SUCCESS;
	}
}