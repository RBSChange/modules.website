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
		$skinId = null;
		$styles = array('modules.generic.frontoffice', 'modules.generic.richtextbo');	
		try
		{
			$documentId = $this->getDocumentIdFromRequest($request);
			if (is_numeric($documentId))
			{
				$document = DocumentHelper::getDocumentInstance($documentId);
				if ($document instanceof website_persistentdocument_page)
				{
					$ps = website_PageService::getInstance();
					$skinId = $document->getSkinId();	
		
					$template = website_PageRessourceService::getInstance()->getPageTemplate($document, false);
					if ($template)
					{
						$styles = array_merge(array('modules.generic.richtextbo'), $template->getScreenStyleIds()); 
					}
						
					$ancestors = $ps->getAncestorsOf($document);
					$ancestors = array_reverse($ancestors);
					foreach ($ancestors as $ancestor)
					{
						if ($ancestor instanceof website_persistentdocument_website || $ancestor instanceof website_persistentdocument_topic)
						{
							if ($ancestor->getStylesheet())
							{
								$styleName = 'modules.website.'.$ancestor->getStylesheet();
								$path = StyleService::getInstance()->getSourceLocation($styleName);
								if ($path)
								{
									$styles[] = $styleName;
									break;
								}
							}
						}
					}
				}
			}
		}
		catch (Exception $e)
		{
			Framework::exception($e);
		}
		
		if ($request->hasParameter('specificstylesheet'))
		{
			$styles[] = $request->getParameter('specificstylesheet');
		}

		$request->setAttribute('stylesheetPath', StyleService::getInstance()->getStylePath($styles, K::XUL, $skinId));
		
		return View::SUCCESS;
	}
}