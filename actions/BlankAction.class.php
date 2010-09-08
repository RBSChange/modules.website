<?php
/**
 * website_BlankAction
 * @package modules.website
 */
class website_BlankAction extends website_Action
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		header("Expires: " . gmdate("D, d M Y H:i:s", time()+60) . " GMT");
		$ss = StyleService::getInstance();
		try
		{
			$documentId = $this->getDocumentIdFromRequest($request);
			if (is_numeric($documentId))
			{
				$document = DocumentHelper::getDocumentInstance($documentId);
				if ($document instanceof website_persistentdocument_page)
				{
					$ss->registerStyle('modules.generic.richtextbo');
					
					website_WebsiteModuleService::getInstance()->setCurrentPageId($document->getId());
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
			$ss->registerStyle($request->getParameter('specificstylesheet'));
		}
		$request->setAttribute('cssInclusion', StyleService::getInstance()->execute(K::HTML));
		return View::SUCCESS;
	}
}