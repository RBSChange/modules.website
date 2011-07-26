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
		$ls = LocaleService::getInstance();
		$data = array();
		$data['label'] = $document->getLabel();
		$data['navigationtitle'] = $document->getNavigationtitle();
		$data['metatitle'] = $document->getMetatitle();
		$data['description'] = $document->getDescription();
		$data['indexingstatus'] = $ls->transBO('m.uixul.bo.general.' . ($document->getIndexingstatus() == 1 ? 'yes' : 'no'), array('ucf'));
		$template = theme_PagetemplateService::getInstance()->getByCodeName($document->getTemplate());
		$data['template'] = $template ? $template->getLabel() : '';
		$data['skin'] = $document->getSkin() ? $document->getSkin()->getLabel() : '';
		$data['startpublicationdate'] = $document->getStartpublicationdate() ? date_Formatter::toDefaultDateTimeBO($document->getUIStartpublicationdate()) : '';
		$data['endpublicationdate'] = $document->getEndpublicationdate() ? date_Formatter::toDefaultDateTimeBO($document->getUIEndpublicationdate()) : '';

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
				$data['navigationVisibility'] = $ls->transBO('m.website.bo.general.visibility.visible', array('ucf'));
				break;
			case 2:
				$data['navigationVisibility'] = $ls->transBO('m.website.bo.general.visibility.hidden-in-menu-only', array('ucf'));
				break;
			case 0:
			default:
				$data['navigationVisibility'] = $ls->transBO('m.website.bo.general.visibility.hidden', array('ucf'));
		}
		return $data;
	}
}