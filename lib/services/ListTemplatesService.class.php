<?php
/**
 * @package modules.website
 * @method website_ListTemplatesService getInstance()
 */
class website_ListTemplatesService extends change_BaseService implements list_ListItemsService
{
	/**
	 * Returns an array of available templates for the website module.
	 * @return array
	 */
	public function getItems()
	{
		$request = change_Controller::getInstance()->getContext()->getRequest();
		$addPageDefaultContent = false;
		$documentId = null;
		$currentTemplate = null;
		
		if ($request->hasParameter('parentid'))
		{
			$documentId = intval($request->getParameter('parentid'));
			$addPageDefaultContent = true;
		}
		else if ($request->hasParameter('pageid'))
		{
			$documentId = intval($request->getParameter('pageid'));
			$page = DocumentHelper::getDocumentInstance($documentId, "modules_website/page");
			$currentTemplate = theme_PagetemplateService::getInstance()->getByCodeName($page->getTemplate());
		}
		
		if (!$documentId)
		{
			$templates = website_TemplateService::getInstance()->createQuery()
				->add(Restrictions::published())
				->find();			
		}
		else
		{
			$templates = theme_ModuleService::getInstance()->getAllowedTemplateForDocumentId($documentId);			
		}
		
		$items = array();
		foreach ($templates as $template)
		{
			if (DocumentHelper::equals($currentTemplate, $template))
			{
				$currentTemplate = null;
			}
			$items[] = new list_Item($template->getLabel(), $template->getCodename());
		}
		
		if (count($templates) && $addPageDefaultContent)
		{
			$codesNames = array();
			foreach ($templates as $template) 
			{
				$codesNames[$template->getCodename()] = $template->getLabel();
			}
			
			$pageContents = website_TemplateService::getInstance()->createQuery()
				->add(Restrictions::published())
				->add(Restrictions::in('template', array_keys($codesNames)))
				->find();
			
			if (count($pageContents))
			{
				foreach ($pageContents as $pageContent) 
				{
					$code = $pageContent->getTemplate();
					$label = $codesNames[$code] . ' / ' . $pageContent->getLabel();
					$items[] = new list_Item($label, $code . '::' . $pageContent->getId());
				}
			}
		}
		
		usort($items, array($this, 'sortItem'));
		
		if ($currentTemplate)
		{
			$label = $currentTemplate->getLabel() . ' (' . f_Locale::translateUI('&modules.website.bo.general.Not-allowed;') .')';
			$item = new list_Item($label , $currentTemplate->getCodename());
			array_unshift($items, $item);
		}
		return $items;
	}
	
	/**
	 * @param list_Item $a
	 * @param list_Item $b
	 */
	public function sortItem($a, $b)
	{
		if ($a->getLabel() === $b->getLabel())
		{
			return 0;		
		}
		return ($a->getLabel() > $b->getLabel()) ? 1 : -1;
	}	
}