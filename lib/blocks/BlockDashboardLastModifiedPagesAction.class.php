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
		$ps = f_permission_PermissionService::getInstance();
		$ms = ModuleService::getInstance();
		$pageService = website_PageService::getInstance();
		
		foreach ($lastModifiedPages as $page)
		{		
			$lastModification = date_Calendar::getInstance($page->getModificationdate());
			if ($lastModification->isToday())
			{
				$status = f_Locale::translateUI('&modules.uixul.bo.datePicker.Calendar.today;') . date_DateFormat::format(date_Converter::convertDateToLocal($lastModification), ', H:i');
			}
			else
			{
				$status = date_DateFormat::format(date_Converter::convertDateToLocal($lastModification), 'l j F Y, H:i');
			}

			$style = '';

			if ($page->getIshomepage())
			{
				$icon = MediaHelper::getIcon('page-home', MediaHelper::SMALL);
			}
			else if ($page->getIsindexpage())
			{
				$icon = MediaHelper::getIcon('page-index', MediaHelper::SMALL);
			}
			else
			{
				$icon = MediaHelper::getIcon('page', MediaHelper::SMALL);
			}

			if ($ps->hasPermission($user, 'modules_'.$moduleName.'.Enabled', $ms->getRootFolderId($moduleName)))
			{
				
				$locate = "openActionUri('website,locateDocument,". str_replace('/', '_', $page->getDocumentModelName()) .",". $page->getId() . "');";
			}
			else
			{
				$locate = '';
			}

			if ($ps->hasAccessToBackofficeAction($user, $moduleName, 'editPageContent', $page->getId()))
			{
				$edit = "openActionUri('website,openDocument,". str_replace('/', '_', $page->getDocumentModelName()) .",". $page->getId() . "');";
			}
			else
			{
				$edit = '';
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
				'status' => ucfirst($status),
				'style' => $style,
				'icon' => $icon,
				'link' => "window.open('$link', 'PreviewWindow', 'menubar=yes, location=yes, toolbar=yes, resizable=yes, scrollbars=yes, status=yes');"
			);
		}
		$request->setAttribute('pages', $widget);
	}
}