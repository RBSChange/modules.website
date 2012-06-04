<?php
/**
 * website_BlockDashboardLastModifiedPagesAction
 * @package modules.website.lib.blocks
 */
class website_BlockDashboardLastModifiedPagesAction extends  dashboard_BlockDashboardAction
{	
	/**
	 * @param f_mvc_Request $request
	 * @param boolean $forEdition
	 */
	protected function setRequestContent($request, $forEdition)
	{
		if ($forEdition)
		{
			return;
		}
		
		$lastModifiedPages = website_PageService::getInstance()->getLastModified();
		$widget = array();

		$user = users_UserService::getInstance()->getCurrentBackEndUser();
		$moduleName = $this->getModuleName();
		$ps = change_PermissionService::getInstance();
		$ms = ModuleService::getInstance();
		foreach ($lastModifiedPages as $page)
		{
			/* @var $page website_persistentdocument_page */
			$attributes = array();
			DocumentHelper::completeBOAttributes($page, $attributes, DocumentHelper::MODE_ICON);
			$icon = MediaHelper::getIcon($attributes['icon'], MediaHelper::SMALL);
			
			$locate = '';
			if ($ps->hasPermission($user, 'modules_'.$moduleName.'.Enabled', $ms->getRootFolderId($moduleName)))
			{
				$locate = "openActionUri('website,locateDocument,". str_replace('/', '_', $page->getDocumentModelName()) .",". $page->getId() . "');";
			}

			$edit = '';
			if ($ps->hasPermission($user, 'modules_'.$moduleName.'.EditContent', $page->getId()))
			{
				$edit = "openActionUri('website,openDocument,". str_replace('/', '_', $page->getDocumentModelName()) .",". $page->getId() . "');";
			}

			$lang = ($page->getCorrectionofid() > 0) ? $page->getLang() : RequestContext::getInstance()->getLang(); 
			$link = LinkHelper::getUIActionLink('website', 'BoDisplay')
				->setQueryParameter('cmpref', $page->getId())
				->setQueryParameter('lang' , $lang)
				->getUrl();
			$widget[] = array(
				'locate' => $locate,
				'edit' => $edit,
				'label' => $page->getLabelAsHtml(),
				'thread' => f_util_HtmlUtils::textToHtml($page->getDocumentService()->getPathOf($page)),
				'status' => date_Formatter::toDefaultDateTimeBO($page->getUIModificationdate()),
				'style' => '',
				'icon' => $icon,
				'link' => $link
			);
		}
		$request->setAttribute('pages', $widget);
	}
}