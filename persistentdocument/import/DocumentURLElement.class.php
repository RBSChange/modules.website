<?php
class website_DocumentURLElement extends import_ScriptBaseElement
{
	public function process()
	{
		$parent = $this->getParent();
		$pp = f_persistentdocument_PersistentProvider::getInstance();
		
		if ($parent instanceof import_ScriptDocumentElement)
		{
			$document = $parent->getPersistentDocument();
			$documentId = $document->getId();
			
			$ds = $document->getDocumentService();
			$websiteId = $ds->getWebsiteId($document); 
			if ($websiteId == null)
			{
				$websiteId = 0;
			}
			
			$url = $this->attributes['url'];
			
			$lang = isset($this->attributes['lang']) ? $this->attributes['lang'] : $document->getLang();
			
			$redirection = isset($this->attributes['redirection']) && $this->attributes['redirection'] === 'true'; 
			if ($redirection)
			{
				$to_url = $this->getDocumentURL($document, $lang);
				if ($to_url !== null)
				{
					$type = (isset($this->attributes['permanently']) &&  $this->attributes['permanently'] === 'true') ? 301 : 302;
					$pp->setUrlRewriting($documentId, $lang, $websiteId, $url, $to_url, $type);
				}
				else
				{
					Framework::warn("Document " . $document->getLabel() . ", $documentId has destination URL for redirection $url");
				}
			}
			else
			{
				$ds->setUrlRewriting($document, $lang, $url);
				$pp->setUrlRewriting($documentId, $lang, $websiteId, $url, null, 200);
			}	
		}
	}
	
	private function getDocumentURL($document, $lang)
	{
		try 
		{
			$url = LinkHelper::getDocumentUrl($document, $lang, array(), false);
			$matches = array();
			if (preg_match('/^https?:\/\/([^\/]*)(\/'.$lang.')?(\/.*)$/', $url, $matches))
			{
				return $matches[3];
			}
		}
		catch (Exception $e)
		{
			Framework::exception($e);
		}
		return null;
	}
}