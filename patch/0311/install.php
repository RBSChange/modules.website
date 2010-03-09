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
		$this->log('Compile URL rewriting rules...');
		exec("change.php compile-url-rewriting");
		
		$tagsRules = $this->loadRules();
		$ts = TagService::getInstance();
		$tm = f_persistentdocument_TransactionManager::getInstance();
		try 
		{
			$tm->beginTransaction();		
			foreach ($tagsRules as $tag => $infos) 
			{
				$this->log('Check tag: ' . $tag);
				$docs = $ts->getDocumentsByTag($tag);
				foreach ($docs as $doc) 
				{
					$ds = $doc->getDocumentService();
					$websiteId = $ds->getWebsiteId($doc);
					if ($websiteId)
					{
						foreach ($doc->getI18nInfo()->getLangs() as $lang) 
						{
							if (isset($infos[$lang]))
							{
								$url = $infos[$lang];
								$this->applyRewriteUrl($doc, $lang, $url, $websiteId);
							}
							elseif (isset($infos['--']))
							{
								$url = $infos['--'];
								$this->applyRewriteUrl($doc, $lang, $url, $websiteId);								
							}
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
						list($tag, $lang, $template) = $this->extractRule($node);
						if (!isset($result[$tag])) {$result[$tag] = array();}
						$result[$tag][$lang] = $template; 
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
					list($tag, $lang, $template) = $this->extractRule($node);
					if (!isset($result[$tag])) {$result[$tag] = array();}
					$result[$tag][$lang] = $template;
				}
			}								
		}
		return $result;
	}
	
	/**
	 * @param f_persistentdocument_PersistentDocument $doc
	 * @param string $lang
	 * @param string $url
	 * @param integer $websiteId
	 */
	private function applyRewriteUrl($doc, $lang, $url, $websiteId)
	{
		$documentId = $doc->getId();
		$this->log("NEW URL $documentId $lang -> $url");
		$oldRewrite = $this->getPersistentProvider()->getUrlRewritingInfo($documentId, $lang);
		$this->getPersistentProvider()->removeUrlRewriting($documentId, $lang);
		$doc->getDocumentService()->setUrlRewriting($doc, $lang, $url);
		$this->getPersistentProvider()->setUrlRewriting($documentId, $lang, $websiteId, $url, null, 200);	
		
		foreach ($oldRewrite as $data) 
		{
			if ($data['redirect_type'] == '200')
			{
				if ($data['from_url'] != $url)
				{
					$this->log("NEW 301 REDIRECT $documentId $lang -> " . $data['from_url']);
					$this->getPersistentProvider()
					->setUrlRewriting($documentId, $lang, $websiteId, $data['from_url'], $url, 301);
				}
			}
			else if ($data['from_url'] != $url)
			{
				$this->log($data['redirect_type'] . " REDIRECT $documentId $lang -> " . $data['from_url']);
				$this->getPersistentProvider()
					->setUrlRewriting($documentId, $lang, $websiteId, $data['from_url'], $url, $data['redirect_type']);
			}
		}		
	}
	
	/**
	 * @param DOMElement $node
	 */
	private function extractRule($node)
	{
		$tag = $node->getAttribute('pageTag');
		$lang = $node->getAttribute('lang');
		if (f_util_StringUtils::isEmpty($lang)) {$lang = '--';}
		$template = $node->getElementsByTagName('template')->item(0)->textContent;
		if (substr($template, -1) !== '/')
		{
			$template .= website_UrlRewritingService::getInstance()->getSuffix();
		}
		return array($tag, $lang, $template);
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