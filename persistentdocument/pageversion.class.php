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
		if ($this->isContextLangAvailable())
		{
			$url = $this->isPublished() ? LinkHelper::getDocumentUrl($this) : 
				LinkHelper::getUIActionLink("website", "BoDisplay")
					->setQueryParameter("cmpref", $this->getId())
					->setQueryParameter("lang", $this->getContextLang())->getUrl();
			$data  = array('id' => $this->getId(), 
						'lang' => $this->getContextLang(),
						'label' => $this->getLabel(),
						'startpublicationdate' => $this->getStartpublicationdate(),
						'endpublicationdate' => $this->getEndpublicationdate(),
						'publicationstatus' => $this->getPublicationstatus(),
						'url' => $url,
						'status' => f_Locale::translateUI(DocumentHelper::getPublicationstatusLocaleKey($this)),
			);
		}
		else 
		{
			$url = $this->isPublished() ? LinkHelper::getDocumentUrl($this, $this->getLang()) : 
				LinkHelper::getUIActionLink("website", "BoDisplay")
					->setQueryParameter("cmpref", $this->getId())
					->setQueryParameter("lang", $this->getLang())->getUrl();
			$data  = array('id' => $this->getId(), 
						'lang' => $this->getLang(),
						'label' => '(' . $this->getLang() . ') ' . $this->getVoLabel(),
						'startpublicationdate' => null,
						'endpublicationdate' => null,
						'publicationstatus' => null,
						'url' => $url,
						'status' => null,
			);			
		}		
		$data['uistartpublicationdate'] = ($data['startpublicationdate']) ? date_DateFormat::format($this->getUIStartpublicationdate()) : '';
		$data['uiendpublicationdate'] = ($data['endpublicationdate']) ? date_DateFormat::format($this->getUIEndpublicationdate()) : '';
		return $data;
	}
}