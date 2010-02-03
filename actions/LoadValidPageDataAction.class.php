<?php
/**
 * website_LoadValidPageDataAction
 * @package modules.website.actions
 */
class website_LoadValidPageDataAction extends task_LoadDataBaseAction
{
	/**
	 * @param website_persistentdocument_page $document
	 * @return Array
	 */
	protected function getInfoForDocument($document)
	{
		$data = array();
		$data['label'] = $document->getLabel();
		$data['navigationtitle'] = $document->getNavigationtitle();
		$data['metatitle'] = $document->getMetatitle();
		$data['description'] = $document->getDescription();
		$data['indexingstatus'] = $document->getIndexingstatus() == 1 ? f_Locale::translate('&modules.website.bo.workflow.ValidPage.Indexing-status-activate;') : f_Locale::translate('&modules.website.bo.workflow.ValidPage.Indexing-status-no-activate;');
		$data['template'] = f_Locale::translate('&modules.website.bo.general.template.'.ucfirst($document->getTemplate()).';');
		$data['skin'] = !is_null($document->getSkin()) ? $document->getSkin()->getLabel() : '';
		$data['startpublicationdate'] = !is_null($document->getStartpublicationdate()) ? date_DateFormat::format(new date_DateTime($document->getUIStartpublicationdate()), f_Locale::translate('&framework.date.date.localized-user-format;')) : '';
		$data['endpublicationdate'] = !is_null($document->getEndpublicationdate()) ? date_DateFormat::format(new date_DateTime($document->getUIEndpublicationdate()), f_Locale::translate('&framework.date.date.localized-user-format;')) : '';
	
		$link = LinkHelper::getUIActionLink('website', 'BoDisplay')
			->setQueryParameter('cmpref', $document->getId())
			->setQueryParameter('lang', RequestContext::getInstance()->getLang());
		if (!$document->getCorrectionofid())
		{
			$link->setQueryParameter('ignoreCorrection', 'true');
		}
		$data['previewUrl'] = $link	->getUrl();
				
		switch ($document->getNavigationVisibility())
		{
			case 1:
				$data['navigationVisibility'] = f_Locale::translate('&modules.website.bo.general.visibility.Visible;');
				break;
			case 2:
				$data['navigationVisibility'] = f_Locale::translate('&modules.website.bo.general.visibility.Hidden-in-menu-only;');
				break;
			case 0:
			default:
				$data['navigationVisibility'] = f_Locale::translate('&modules.website.bo.general.visibility.Hidden;');
		}
		return $data;
	}
}