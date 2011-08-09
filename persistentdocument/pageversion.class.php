<?php
/**
 * website_persistentdocument_pageversion
 * @package website
 */
class website_persistentdocument_pageversion extends website_persistentdocument_pageversionbase 
{
	/**
	 * @return array
	 */
	public function getInfoForPageGroup()
	{
		$ls = LocaleService::getInstance();
		$correction = ($this->hasCorrection() ? DocumentHelper::getDocumentInstance($this->getCorrectionid()) : null);
		if ($this->isContextLangAvailable())
		{
			$url = $this->isPublished() ? LinkHelper::getDocumentUrl($this) : 
				LinkHelper::getUIActionLink("website", "BoDisplay")
					->setQueryParameter("cmpref", $this->getId())
					->setQueryParameter("lang", $this->getContextLang())->getUrl();
			$data  = array(
				'id' => $this->getId(), 
				'lang' => $this->getContextLang(),
				'label' => $this->getLabel(),
				'startpublicationdate' => $this->getStartpublicationdate(),
				'endpublicationdate' => $this->getEndpublicationdate(),
				'publicationstatus' => $this->getPublicationstatus(),
				'url' => $url,
				'status' => $ls->transBO(DocumentHelper::getStatusLocaleKey($this))
			);
			if ($correction !== null)
			{
				$url = LinkHelper::getUIActionLink("website", "BoDisplay")
					->setQueryParameter("cmpref", $correction->getId())
					->setQueryParameter("lang", $this->getContextLang())->getUrl();
				$data['id'] = $correction->getId();
				$data['label'] = $correction->getLabel()." (".$data['label'].")";
				$data['startpublicationdate'] = $correction->getStartpublicationdate()." (".$data['startpublicationdate'].")";
				$data['endpublicationdate'] = $correction->getEndpublicationdate()." (".$data['endpublicationdate'].")";
				$data['publicationstatus'] = $correction->getPublicationstatus();
				$data['url'] = $url;
				$data['status'] = $ls->transBO(DocumentHelper::getStatusLocaleKey($correction));
			}
		}
		else
		{
			$url = $this->isPublished() ? LinkHelper::getDocumentUrl($this, $this->getLang()) : 
				LinkHelper::getUIActionLink("website", "BoDisplay")
					->setQueryParameter("cmpref", $this->getId())
					->setQueryParameter("lang", $this->getLang())->getUrl();
			$data  = array(
				'id' => $this->getId(), 
				'lang' => $this->getLang(),
				'label' => '(' . $this->getLang() . ') ' . $this->getVoLabel(),
				'startpublicationdate' => null,
				'endpublicationdate' => null,
				'publicationstatus' => null,
				'url' => $url,
				'status' => null
			);			
		}		
		$data['uistartpublicationdate'] = ($data['startpublicationdate']) ? date_Formatter::toDefaultDateTimeBO($this->getUIStartpublicationdate()) : '';
		$data['uiendpublicationdate'] = ($data['endpublicationdate']) ? date_Formatter::toDefaultDateTimeBO($this->getUIEndpublicationdate()) : '';
		return $data;
	}
}