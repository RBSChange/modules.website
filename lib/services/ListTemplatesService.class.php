<?php
class website_ListTemplatesService extends BaseService implements list_ListItemsService
{
	/**
	 * @var website_ListTemplatesService
	 */
	private static $instance;
	

	/**
	 * @return website_ListTemplatesService
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = self::getServiceClassInstance(get_class());
		}
		return self::$instance;
	}
	

	/**
	 * Returns an array of available templates for the website module.
	 *
	 * @return array
	 */
	public function getItems()
	{
		
		$items = array();

		
		$items[] = new list_Item(f_Locale::translateUI('&modules.website.bo.general.Template-list-staticLabel;'), '', 'group');
		$parentId = Controller::getInstance()->getContext()->getRequest()->getParameter(K::PARENT_ID_ACCESSOR, null);
		foreach (website_PageRessourceService::getInstance()->getTemplateDefinitionsByParentId($parentId) as $templateProps)
		{
			if (isset($templateProps['group']))
			{
				$items[] = new list_Item(f_Locale::translateUI($templateProps['group']), '', 'group');
			}
			$items[] = new list_Item(f_Locale::translateUI($templateProps['label']), $templateProps['file']);
		}

		
		$dynamicTemplates = website_TemplateService::getInstance()->getDynamicTemplates();
		
		if (count($dynamicTemplates))
		{
			$items[] = new list_Item(f_Locale::translateUI('&modules.website.bo.general.Template-list-dynamicLabel;'), '', 'group');
			
			foreach ($dynamicTemplates as $dynamicTemplate)
			{
				$items[] = new list_Item($dynamicTemplate->getLabel(), 'cmpref::' . $dynamicTemplate->getId());
			}
		}
		
		return $items;
	}

}