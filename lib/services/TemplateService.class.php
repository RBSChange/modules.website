<?php
class website_TemplateService extends f_persistentdocument_DocumentService
{
	/**
	 * @var website_TemplateService
	 */
	private static $instance;
	
	/**
	 * @return website_TemplateService
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = self::getServiceClassInstance(get_class());
		}
		return self::$instance;
	}
	
	/**
	 * @return website_persistentdocument_template
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_website/template');
	}
	
	/**
	 * Create a query based on 'modules_modules_website/template' model
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->pp->createQuery('modules_website/template');
	}
	
	/**
	 * Retrieve all available "dynamic" templates.
	 *
	 * @return array<website_persistentdocument_template>
	 */
	public function getDynamicTemplates()
	{
		$systemFolderId = ModuleService::getInstance()->getSystemFolderId('website');
		
		$systemFolder = DocumentHelper::getDocumentInstance($systemFolderId);
		
		return $this->getChildrenOf($systemFolder, 'modules_website/template');
	}
	

	/**
	 * Indicates if the given template identifier is related to a "dynamic" template.
	 *
	 * @param string $identifier
	 * @return boolean
	 */
	public function isDynamicTemplate($identifier)
	{
		$isDynamicTemplate = false;
		
		if (f_util_StringUtils::beginsWith($identifier, 'cmpref::'))
		{
			list($cmprefHeader, $cmprefValue) = explode('::', $identifier);
			try
			{
				$document = DocumentHelper::getDocumentInstance(intval($cmprefValue));
				
				if ($document && ($document->getDocumentModelName() == 'modules_website/template'))
				{
					$isDynamicTemplate = true;
				}
			} catch (Exception $e)
			{
				Framework::exception($e);
			}
		}
		return $isDynamicTemplate;
	}
	

	/**
	 * Returns a "dynamic" template document from a string identifier (for example : "cmpref::12301").
	 *
	 * @param string $identifier
	 * @return website_persistentdocument_template
	 */
	public function getDynamicTemplate($identifier)
	{
		if (f_util_StringUtils::beginsWith($identifier, 'cmpref::'))
		{
			list($cmprefHeader, $identifier) = explode('::', $identifier);
		}
		
		return DocumentHelper::getDocumentInstance(intval($identifier));
	}
	

	/**
	 * Return template content
	 * @param string $templateName
	 * @return DOMDocument
	 */
	public function getContent($templateName)
	{
		
		$templateContent = null;
		if ($this->isDynamicTemplate($templateName))
		{
			try
			{
				$template = $this->getDynamicTemplate($templateName);
				$templateContent = $template->getContent();
			} catch (Exception $e)
			{
				Framework::exception($e);
				throw new TemplateNotFoundException($templateName . ' (dynamic)');
			}
		}
		else
		{
			$template = TemplateLoader::getInstance()->setPackageName('modules_website')->setDirectory('templates')->setMimeContentType('xul')->load($templateName);
			if ($template === null)
			{
				throw new TemplateNotFoundException($templateName.".xul");
			}
			// TODO: make a context
			$templateContent = $template->execute();
		}
		
		$content = new DOMDocument('1.0', 'UTF-8');
		if ($templateContent)
		{
			$content->loadXML($templateContent);
		}
		
		return $content;
	}
	
	/**
	 * @param Integer $index
	 * @return DOMElement
	 */
	private function getEmptyFreeBloc($index)
	{
		$emptyBloc = new DOMDocument('1.0', 'UTF-8');
		$emptyBloc->loadXML('<wlayout id="freeLayout' . $index . '" type="free" editable="true"><wtemplate id="freeTemplate' . $index . '"><grid><columns><column /></columns><rows><row id="freeContainer' . $index . '" /></rows></grid></wtemplate><wlocationset><wlocation target="freeContainer' . $index . '" editable="true" /></wlocationset><wblockset><wblock target="freeContainer' . $index . '" type="empty" editable="true" movable="true"/></wblockset></wlayout>');
		return $emptyBloc->documentElement;
	}
	
	/**
	 * Fill empty free blocks with all the required elements.
	 *
	 * @param DOMDocument $content
	 * @return DOMDocument
	 */
	public function initializeEmptyFreeBlocks($content)
	{
		$blockIndex = 1;
		foreach ($content->getElementsByTagName('wblock') as $blockMatch)
		{
			if ($blockMatch->getAttribute('type') == 'free' && $blockMatch->childNodes->length == 0)
			{
				$newContent = $content->importNode($this->getEmptyFreeBloc($blockIndex), true);
				$blockMatch->appendChild($newContent);
			}
			$blockIndex++;
		}
		
		return $content;
	}
	
	/**
	 * Get the list of the id's of all the change:content tags in the template
	 * $templateName 
	 *
	 * @param String $templateName
	 * @return array<String>
	 */
	public function getChangeContentIds($templateName)
	{
		try
		{
			$contentIds = array();
			$DOMDoc = $this->getContent($templateName);
			if ($DOMDoc)
			{
				$DOMXpath = new DOMXPath($DOMDoc);
				$DOMXpath->registerNamespace('change', website_PageService::CHANGE_PAGE_EDITOR_NS);
				$templates = $DOMXpath->query('//change:template[@content-type="html"]');
				if ($templates->length == 0)
				{
					$templates = $DOMXpath->query('//change:template');
				}
				if ($templates->length == 0)
				{
					Framework::warn("template $templateName has no change:template tag");
				} else
				{
					foreach ($DOMXpath->query('.//change:content', $templates->item(0)) as $content)
					{
						$contentIds[] = $content->getAttribute('id');
					}
				}
			}		
		} catch (Exception $e)
		{
			Framework::exception($e);
			return array();
		}
		return $contentIds;
	}
	
	public function getStaticTemplates()
	{
		$results = array();
		$displayFilePath = FileResolver::getInstance()->setPackageName('modules_website')->setDirectory('config')->getPath('display.xml');
		if ($displayFilePath === null)
		{
			throw new BaseException(__METHOD__ . ': Could not resolve display.xml file', 'modules.website.backoffice.general.NoDisplayFileError');
		}
		
		$domDocument = new DOMDocument();
		if ($domDocument->load($displayFilePath) === false)
		{
			throw new BaseException(__METHOD__ . ': display.xml file is not a valid xml', 'modules.website.backoffice.general.InvalidDisplayFileError');
		}
		
		$templates = $domDocument->getElementsByTagName('display');
		foreach ($templates as $template)
		{
			$templateProps = array();
			if ($template->hasAttribute("group"))
			{
				$templateProps['group'] = $template->getAttribute("group");
			}
			$templateProps['file'] = $template->getAttribute("file");;
			$templateProps['label'] = $template->getAttribute("label");
			$templateProps['style'] = $template->getAttribute("style");
			$results[] = $templateProps;
		}
		return $results;
	}
}