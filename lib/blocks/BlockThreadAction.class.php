<?php
class website_BlockThreadAction extends website_BlockAction
{
	/**
	 * @see f_mvc_Action::getCacheDependencies()
	 *
	 * @return array<string>
	 */
	public function getCacheDependencies()
	{
		return array("modules_website/page", "modules_website/pagegroup", 
			"modules_website/pagereference", "modules_website/pageversion", 
			"modules_website/topic", "modules_website/systemtopic", "modules_website/website");
	}
	
	/**
	 * @param website_BlockActionRequest $request
	 * @return array<mixed>
	 */
	public function getCacheKeyParameters($request)
	{
		return array("context->id" => $this->getPage()->getId(), 
			"context->label" => $this->getPage()->getNavigationtitle(), 
			"lang->id" => RequestContext::getInstance()->getLang());
	}
	
	/**
	 * @see website_BlockAction::execute()
	 *
	 * @param website_BlockActionRequest $request
	 * @param website_BlockActionResponse $response
	 * @return String
	 */
	function execute($request, $response)
	{
		$pageContext = $this->getPage();
		$pageDocument = $pageContext->getPersistentPage();
		$breadcrumb = array();		
		
		if (! $pageDocument->getIsHomePage())
		{
			foreach ($pageContext->getAncestorIds() as $ancestorId)
			{
				$ancestor = DocumentHelper::getDocumentInstance($ancestorId);
				if (! $ancestor->isPublished())
				{
					continue;
				}
				
				if ($ancestor instanceof website_persistentdocument_website)
				{
					$siteUrl = LinkHelper::getDocumentUrl($ancestor);
					$homeTitle = f_Locale::translate('&modules.website.frontoffice.thread.Homepage-href-name;');
					
					$breadcrumb[] = array('navigationtitle' => $homeTitle, 'href' => $siteUrl, 'class' => 'first');
					$pageContext->addLink("home", "text/html", $siteUrl, $homeTitle);
				}
				else if ($ancestor instanceof website_persistentdocument_topic)
				{
					if ($ancestor->getNavigationVisibility() != 1)
					{
						continue;
					}
					$breadcrumb[] = array('navigationtitle' => $ancestor->getLabel(), 'href' => LinkHelper::getDocumentUrl($ancestor));
					
				}
			}
		}
		
		$class = (count($breadcrumb) == 0) ? 'first last' : 'last';
		$breadcrumb[] = array('navigationtitle' => $pageContext->getNavigationtitle(), 'class' => $class);
		$request->setAttribute('breadcrumb', $breadcrumb);
		return website_BlockView::SUCCESS;
	}
}