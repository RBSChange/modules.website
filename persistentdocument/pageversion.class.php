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
			$data  = array('id' => $this->getId(), 
						'lang' => $this->getContextLang(),
						'label' => $this->getLabel(),
						'startpublicationdate' => $this->getStartpublicationdate(),
						'endpublicationdate' => $this->getEndpublicationdate(),
						'publicationstatus' => $this->getPublicationstatus(),
						'status' => f_Locale::translateUI(DocumentHelper::getPublicationstatusLocaleKey($this)),
			);
		}
		else 
		{
			$data  = array('id' => $this->getId(), 
						'lang' => $this->getLang(),
						'label' => '(' . $this->getLang() . ') ' . $this->getVoLabel(),
						'startpublicationdate' => null,
						'endpublicationdate' => null,
						'publicationstatus' => null,
						'status' => null,
			);			
		}		
		$data['uistartpublicationdate'] = ($data['startpublicationdate']) ? date_DateFormat::format($this->getUIStartpublicationdate()) : '';
		$data['uiendpublicationdate'] = ($data['endpublicationdate']) ? date_DateFormat::format($this->getUIEndpublicationdate()) : '';
		return $data;
	}
}