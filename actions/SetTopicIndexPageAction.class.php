<?php
class website_SetTopicIndexPageAction extends change_JSONAction
{
	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	public function _execute($context, $request)
	{
		$page = $this->getDocumentInstanceFromRequest($request);
		$setPageRef = $request->getParameter('PageRef', 'false') == 'true';
		if ($setPageRef)
		{
			$refs = website_PagereferenceService::getInstance()->getPagesReferenceByPage($page);
			foreach ($refs as $pageRef) 
			{
				try
				{
					if (!$pageRef->getIsIndexPage())
					{
						website_WebsiteModuleService::getInstance()->setIndexPage($pageRef, true);
						$this->logAction($pageRef);
					}
				}
				catch (Exception $e)
				{
					Framework::exception($e);
				    return $this->sendJSONError(f_Locale::translateUI('&modules.website.bo.general.set-index-page-ref-error;', array('id' => $pageRef->getId())));
				}
			}
		}
		else
		{
			try
			{
				website_WebsiteModuleService::getInstance()->setIndexPage($page, true);
				$this->logAction($page);
			}
			catch (Exception $e)
			{
				Framework::exception($e);
			    return $this->sendJSONError(f_Locale::translateUI('&modules.website.bo.general.set-index-page-error;'));
			}
		}
		
		return $this->sendJSON(array('cmpref' => $page->getId(), 'documentversion' => $page->getDocumentversion()));
	}
}