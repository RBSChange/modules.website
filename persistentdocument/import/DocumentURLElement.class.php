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
			$moduleName = $document->getPersistentModel()->getModuleName();
			$actionName = 'ViewDetail';
			$documentId = $document->getId();
			
			$ds = $document->getDocumentService();
			$websiteId = $ds->getWebsiteId($document); 
			if ($websiteId == null)
			{
				Framework::warn("Document " . $document->getLabel() . ", has no website");
				return;
			}
			
			$website = website_persistentdocument_websitebase::getInstanceById($websiteId);	
			$url = $this->attributes['url'];
			
			//TODO use context lang ?
			$lang = isset($this->attributes['lang']) ? $this->attributes['lang'] : $document->getLang();
			
			$redirection = isset($this->attributes['redirection']) && $this->attributes['redirection'] === 'true'; 
			if ($redirection)
			{
				$type = (isset($this->attributes['permanently']) &&  $this->attributes['permanently'] === 'true') ? 301 : 302;
				website_UrlRewritingService::getInstance()->setCustomRedirectPath($url, $type, $document, $website, $lang);
			}
			else
			{
				website_UrlRewritingService::getInstance()->setCustomPath($url, $document, $website, $lang);
			}	
		}
	}
}