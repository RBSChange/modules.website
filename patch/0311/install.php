<?php
/**
 * website_patch_0311
 * @package modules.website
 */
class website_patch_0311 extends patch_BasePatch
{
	/**
	 * Entry point of the patch execution.
	 */
	public function execute()
	{
		$tagsRules = $this->loadRules();
		$ts = TagService::getInstance();
		$tm = f_persistentdocument_TransactionManager::getInstance();
		$pp = $tm->getPersistentProvider();
		
		try 
		{
			$tm->beginTransaction();
			
			foreach ($tagsRules as $tagRule) 
			{
				$docs = $ts->getDocumentsByTag($tagRule['tag']);
				foreach ($docs as $doc) 
				{
					if ($doc->isLangAvailable($tagRule['lang']))
					{
						$documentId = $doc->getId();
						$url = $tagRule['template'];
						$lang = $tagRule['lang'];
						$ds = $doc->getDocumentService();
						$websiteId = $ds->getWebsiteId($doc);
						if ($websiteId)
						{
							$this->log("setUrlRewriting $documentId $lang -> $url");
							$pp->removeUrlRewriting($documentId, $lang);
							$ds->setUrlRewriting($doc, $lang, $url);
							$pp->setUrlRewriting($documentId, $lang, $websiteId, $url, null, 200);
						}
					}
				}
			}
			$tm->commit();
		}
		catch (Exception $e)
		{
			$tm->rollBack($e);
		}
	}

	private function loadRules()
	{
		$result = array();
		$modules = ModuleService::getInstance()->getModules();
		foreach ($modules as $module)
		{
			$filePath = FileResolver::getInstance()->setPackageName($module)->getPath('/config/urlrewriting.xml');
			if ($filePath)
			{
				$doc = f_util_DOMUtils::fromPath($filePath);
				$nodes = $doc->find('//rule[@pageTag]');
				foreach ($nodes as $node) 
				{
					if ($node->getElementsByTagName('parameter')->length == 0)
					{
						$result[] = $this->extractRule($node);
					}
				}								
			}
		}
		$filePath = f_util_FileUtils::buildWebeditPath('config', 'urlrewriting.xml');
		if (is_readable($filePath))
		{
			$doc = f_util_DOMUtils::fromPath($filePath);
			$nodes = $doc->find('//rule[@pageTag]');
			foreach ($nodes as $node) 
			{
				if ($node->getElementsByTagName('parameter')->length == 0)
				{
					$result[] = $this->extractRule($node);
				}
			}								
		}
		return $result;
	}
	
	/**
	 * @param DOMElement $node
	 */
	private function extractRule($node)
	{
		$tag = $node->getAttribute('pageTag');
		$lang = $node->getAttribute('lang');
		$template = $node->getElementsByTagName('template')->item(0)->textContent;
		return array('tag' => $tag, 'lang' => $lang, 'template' => $template);
	}
	
	/**
	 * @return String
	 */
	protected final function getModuleName()
	{
		return 'website';
	}

	/**
	 * @return String
	 */
	protected final function getNumber()
	{
		return '0311';
	}
}