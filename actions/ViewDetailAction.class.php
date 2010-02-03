<?php
/**
 * @date Wed Jan 31 15:33:29 CET 2007
 * @author INTbonjF
 */
class website_ViewDetailAction extends f_action_BaseAction
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		try 
		{
			$cmpref = $request->getModuleParameter('website', 'cmpref');
			if (intval($cmpref))
			{
				$document = DocumentHelper::getDocumentInstance($cmpref);
				if ($document instanceof website_persistentdocument_topic)
				{
					if ($document->hasPublishedIndexPage())
					{
						$page = $document->getIndexPage();
						if ($page->getNavigationVisibility() == WebsiteConstants::VISIBILITY_VISIBLE)
						{
							$context->getController()->redirectToUrl(LinkHelper::getDocumentUrl($page));
							return View::NONE;	
						}
						else
						{
							$value = array('cmpref' => $page->getId());		
							if (is_array($request->getParameter('websiteParam')))
							{
								$request->setParameter('websiteParam', array_merge($request->getParameter('websiteParam'), $value));
							}
							else
							{
								$request->setParameter('websiteParam', $value);
							}
							$context->getController()->forward('website', 'Display');
							return View::NONE;	
						}
					}
					$context->getController()->forward('website', 'Error404');
					return View::NONE;
				}
				else if ($document instanceof website_persistentdocument_website)
				{
					$context->getController()->redirectToUrl(LinkHelper::getDocumentUrl($document));
				}
			}
			$context->getController()->forward('website', 'Display');
		}
		catch (BaseException $e)
		{
			if ($e->getMessage()== "object-not-found" )
			{
				$context->getController()->forward('website', 'Error404');
			}
			else 
			{
				throw $e;
			}
		}
		return View::NONE;
	}


	public function isSecure()
	{
		return false;
	}
}