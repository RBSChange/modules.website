<?php

class website_PageRessourceService extends BaseService
{
	const GLOBAL_SCREEN_NAME = 'frontoffice';
	const GLOBAL_PRINT_NAME = 'print';
	const GLOBAL_DASHBOARD_NAME = 'dashboard';

	/**
	 * @var f_web_CSSVariables
	 */
	private $skin;

	/**
	 * @var website_persistentdocument_page
	 */
	private $page;

	/**
	 * @var website_PageRessourceService
	 */
	private static $instance;


	/**
	 * @return website_PageRessourceService
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
	 * @param f_web_CSSVariables $skin
	 */
	public function setSkin($skin)
	{
		$this->skin = $skin;
	}

	/**
	 * @return f_web_CSSVariables
	 */
	private function getSkin()
	{
		return $this->skin;
	}

	/**
	 * @return website_persistentdocument_page
	 */
	public function getPage()
	{
		return $this->page;
	}

	/**
	 * @param website_persistentdocument_page $page
	 */
	public function setPage($page)
	{
		$this->page = $page;
	}

	/**
	 * @param website_persistentdocument_page $page
	 * @return DOMDocument
	 */
	public function getPagetemplateAsDOMDocument($page)
	{
		$page->setTemplateUserAgent('all.all');
		return website_TemplateService::getInstance()->getContent($page->getTemplate());
	}

	/**
	 * @param website_persistentdocument_page $page
	 * @return DOMDocument
	 */
	public function getBackpagetemplateAsDOMDocument($page)
	{
		$page->setTemplateUserAgent('all.all');
		return website_TemplateService::getInstance()->getContent($page->getTemplate());
	}

	/**
	 * Gets the <link .../> tag for the combination of all frontoffice (and website/generic richtext) stylesheets
	 *
	 * @return String
	 */
	public function getGlobalScreenStylesheetInclusion()
	{
		$rc = RequestContext::getInstance();
		$relativePath = $this->getStylesheetRelativePath(self::GLOBAL_SCREEN_NAME, $rc->getUserAgentType(), $rc->getUserAgentTypeVersion(), $rc->getProtocol());
		return $this->buildStylesheetInclusion($relativePath, "screen");
	}

	/**
	 * Gets the <style media="screen">.... tag for the combination of all frontoffice (and website/generic richtext) stylesheets
	 * @return string
	 */
	public function getGlobalScreenStylesheetInLine()
	{
		$tmpFile = f_util_FileUtils::getTmpFile('css');
		$this->buildGlobalScreenStylesheetAtPath($tmpFile);
		$css = file_get_contents($tmpFile);
		unlink($tmpFile);
		$basUrl = 'http://' . website_WebsiteModuleService::getInstance()->getCurrentWebsite()->getDomain() . '/';
		return '<base href="'. $basUrl .'" /><style type="text/css" media="screen">' . $css . '</style>';
	}

	public function getGlobalScreenStylesheet($engine, $version, $protocol)
	{
		$relativePath = $this->getStylesheetRelativePath(self::GLOBAL_SCREEN_NAME, $engine, $version, $protocol);

		$absolutePath = f_util_FileUtils::buildWebappPath('www', $relativePath);
		if (!file_exists($absolutePath) || file_exists($relativePath.".deleted") || Framework::inDevelopmentMode())
		{
			$this->buildGlobalScreenStylesheetAtPath($absolutePath);
		}
		if (file_exists($relativePath.".deleted"))
		{
			unlink($relativePath.".deleted");
		}
		return file_get_contents($relativePath);
	}


	/**
	 * Gets the <link .../> tag for the combination of all frontoffice (and website/generic richtext) stylesheets
	 *
	 * @return String
	 */
	public function getDashboardStylesheetInclusion()
	{
		$rc = RequestContext::getInstance();
		$relativePath = $this->getStylesheetRelativePath(self::GLOBAL_DASHBOARD_NAME, $rc->getUserAgentType(), $rc->getUserAgentTypeVersion(), $rc->getProtocol());
		return $this->buildStylesheetInclusion($relativePath, "screen");
	}

	public function getDashboardStylesheet($engine, $version, $protocol)
	{
		$relativePath = $this->getStylesheetRelativePath(self::GLOBAL_DASHBOARD_NAME, $engine, $version, $protocol);

		$absolutePath = f_util_FileUtils::buildWebappPath('www', $relativePath);
		if (!file_exists($absolutePath) || file_exists($relativePath.".deleted")  || Framework::inDevelopmentMode())
		{
			$this->buildDashboardStylesheetAtPath($absolutePath);
		}
		if (file_exists($relativePath.".deleted"))
		{
			unlink($relativePath.".deleted");
		}
		return file_get_contents($relativePath);
	}

	/**
	 * @param String $path
	 */
	private function buildDashboardStylesheetAtPath($path)
	{
		f_util_FileUtils::mkdir(dirname($path));
		$fh = fopen($path, 'w');
		$stylesheetIds = array('modules.generic.frontoffice', 'modules.generic.richtext', 'modules.dashboard.dashboard');
		foreach ($stylesheetIds as $stylesheetId)
		{
			$this->appendStylesheetContent($fh, $stylesheetId, K::HTML);
		}
		fclose($fh);
	}


	/**
	 * Gets the <link .../> tag for the combination of all print stylesheets
	 *
	 * @return String
	 */
	public function getGlobalPrintStylesheetInclusion()
	{
		$rc = RequestContext::getInstance();
		$relativePath = $this->getStylesheetRelativePath(self::GLOBAL_PRINT_NAME, $rc->getUserAgentType(), $rc->getUserAgentTypeVersion(), $rc->getProtocol());
		return $this->buildStylesheetInclusion($relativePath, "print");
	}

	public function getGlobalPrintStylesheet($engine, $version, $protocol)
	{
		$relativePath = $this->getStylesheetRelativePath(self::GLOBAL_PRINT_NAME, $engine, $version, $protocol);
		$absolutePath = f_util_FileUtils::buildWebappPath('www', $relativePath);
		if (!file_exists($absolutePath) || file_exists($relativePath.".deleted") || Framework::inDevelopmentMode())
		{
			$this->buildGlobalPrintStylesheetAtPath($absolutePath);
		}
		if (file_exists($relativePath.".deleted"))
		{
			unlink($relativePath.".deleted");
		}
		return file_get_contents($relativePath);
	}

	public function getStylesheet($name, $engine, $version, $protocol)
	{
		$relativePath = $this->getStylesheetRelativePath($name, $engine, $version, $protocol);
		$absolutePath = f_util_FileUtils::buildWebappPath('www', $relativePath);
		if (!file_exists($absolutePath) || file_exists($relativePath.".deleted") || Framework::inDevelopmentMode())
		{
			f_util_FileUtils::mkdir(dirname($absolutePath));
			$fh = fopen($absolutePath, 'w');
			$this->appendStylesheetContent($fh, $name, K::HTML);
			fclose($fh);
		}
		if (file_exists($relativePath.".deleted"))
		{
			unlink($relativePath.".deleted");
		}
		return file_get_contents($relativePath);
	}



	/**
	 * Gets the <link .../> tag for the current page's template stylesheet
	 *
	 * @return String
	 */
	public function getPageStylesheetInclusion()
	{
		$rc = RequestContext::getInstance();
		$page = $this->getPage();
		if ($page === null)
		{
			return null;
		}

		$stylesheetName = $this->getStylesheetNameForCurrentPage();
		if ($stylesheetName == null)
		{
			return null;
		}
		$relativePath = $this->getStylesheetRelativePath($stylesheetName, $rc->getUserAgentType(), $rc->getUserAgentTypeVersion(), $rc->getProtocol());		
		return $this->buildStylesheetInclusion($relativePath, "screen");
	}

	/**
	 * Gets the <style media="screen">.... tag for the current page's template stylesheet
	 * @return String
	 */
	public function getPageStylesheetInLine()
	{
		$page = $this->getPage();
		if ($page === null)
		{
			return null;
		}

		$stylesheetName = $this->getStylesheetNameForCurrentPage();
		if ($stylesheetName == null)
		{
			return null;
		}
		$ss = StyleService::getInstance();
		$engine = $ss->getFullEngineName();
		$css = $ss->getCSS($stylesheetName, $engine, $this->getSkin());
		if ($css)
		{
			return '<style type="text/css" media="screen">' . $css . '</style>';
		}
		return null;
	}

	/**
	 * @param Integer $parentId
	 */
	public function getTemplateDefinitionsByParentId($parentId)
	{
		//$parentId is unused in the default implementation...
		return website_TemplateService::getInstance()->getStaticTemplates();
	}

	/**
	 * @return String
	 */
	protected function getStylesheetNameForCurrentPage()
	{
		$page = $this->getPage();
		if ($page === null)
		{
			return null;
		}
		return $this->getStylesheetNameForPage($page);
	}

	public function getStylesheetNameForPage($page)
	{
		$templateName = $page->getTemplate();
		$pathWhereToFindDisplays = FileResolver::getInstance()->setPackageName('modules_website')->setDirectory('config')->getPath('display.xml');
		$displayConfig = new DOMDocument('1.0', 'UTF-8');
		$displayConfig->load($pathWhereToFindDisplays);
		foreach ($displayConfig->getElementsByTagName('display') as $display)
		{
			if (($display->getAttribute('file') == $templateName) && $display->hasAttribute('style'))
			{
				$stylesheetName = $display->getAttribute('style');
				$stylesheetName = explode('.', $stylesheetName);
				$stylesheetName = $stylesheetName[count($stylesheetName) - 1];
				return 'modules.website.' . $stylesheetName;
			}
		}
		return null;
	}

	/**
	 * Returns the path of the "Global template" used to render the page
	 *
	 * @return String
	 */
	public function getGlobalTemplate()
	{
		return TemplateResolver::getInstance()->setPackageName('modules_website')->setDirectory('templates')
		->setMimeContentType('php')
		->getPath('PageDynamic-ContentBasis');
	}

	/**
	 * Returns the array of scripts for the page
	 *
	 * @return Array
	 */

	public function getAvailableScripts()
	{
		$pageTemplate = ($this->getPage() !== null) ? $this->getPage()->getTemplate() : "";
		$frontOfficeScriptsCache = f_util_FileUtils::buildCachePath("frontofficeScripts.". $pageTemplate);
		if (AG_DEVELOPMENT_MODE)
		{
			if (file_exists($frontOfficeScriptsCache))
			{
				unlink($frontOfficeScriptsCache);
			}
			return $this->_getAvailableScripts();
		}
		if (!file_exists($frontOfficeScriptsCache))
		{
			$availableScripts = $this->_getAvailableScripts();
			f_util_FileUtils::writeAndCreateContainer($frontOfficeScriptsCache, serialize($availableScripts), f_util_FileUtils::OVERRIDE);
			return $availableScripts;
		}
		return unserialize(file_get_contents($frontOfficeScriptsCache));
	}

	private function _getAvailableScripts()
	{
		$fileResolver = FileResolver::getInstance();

		$availableScripts = array();
		foreach (ModuleService::getInstance()->getModulesObj() as $module)
		{
			$scriptPath = $fileResolver->setPackageName($module->getFullName())->setDirectory('lib')->getPath('frontoffice.js');
			if ($scriptPath)
			{
				$availableScripts[] = 'modules.'.$module->getName().'.lib.frontoffice';
			}
		}
		$page = $this->getPage();
		if ($page !== null)
		{
			$templateName = $page->getTemplate();
			$pathWhereToFindDisplays = $fileResolver->setPackageName('modules_website')->setDirectory('config')->getPath('display.xml');
			$displayConfig = new DOMDocument('1.0', 'UTF-8');
			$displayConfig->load($pathWhereToFindDisplays);

			foreach ($displayConfig->getElementsByTagName('display') as $display)
			{
				if (($display->getAttribute('file') == $templateName) && $display->hasAttribute('script'))
				{
					$scriptName = $display->getAttribute('script');
					$scriptPath = $fileResolver->setPackageName('modules_website')->setDirectory('lib')->getPath($scriptName . '.js');
					if ($scriptPath)
					{
						$availableScripts[] = 'modules.website.lib.' . $scriptName;
					}
					break;
				}
			}
		}
		return $availableScripts;
	}

	/**
	 * @param String $name
	 * @param String $engine
	 * @param String $version
	 * @param String $https
	 * @return String
	 */
	private function getStylesheetRelativePath($name, $engine, $version, $protocol = 'http')
	{
		$fullName = $name;
		if ($this->skin)
		{
			$fullName .= '-' . $this->skin->getIdentifier();
		}
		$fullName .= '.css';
		$lang = RequestContext::getInstance()->getLang();
		$websiteId = website_WebsiteModuleService::getInstance()->getCurrentWebsite()->getId();
		return f_util_FileUtils::buildPath('cache', 'css', $protocol , $websiteId, $lang, $engine, $version, $fullName);
	}

	/**
	 * @param String $styleSheetRelativePath
	 * @param String $mediaType
	 * @return String
	 */
	private function buildStylesheetInclusion($styleSheetRelativePath, $mediaType)
	{
		$inclusionSrc = LinkHelper::getRessourceLink('/' . $styleSheetRelativePath)->getUrl();
		return '<link rel="stylesheet" href="' . $inclusionSrc . '" type="text/css" media="' . $mediaType . '" />';
	}

	/**
	 * @param String $path
	 */
	private function buildGlobalScreenStylesheetAtPath($path)
	{

		f_util_FileUtils::mkdir(dirname($path));
		$fh = fopen($path, 'w');
		foreach ($this->getStylesheetIdsForGlobalScreen() as $stylesheetId)
		{
			$this->appendStylesheetContent($fh, $stylesheetId, K::HTML);
		}
		fclose($fh);
	}

	/**
	 * @return String[]
	 */
	protected function getStylesheetIdsForGlobalScreen()
	{
		$ss = StyleService::getInstance();
		$stylesheetArray = array('modules.generic.frontoffice', 'modules.generic.richtext', 'modules.website.frontoffice', 'modules.website.richtext');
		foreach (ModuleService::getInstance()->getModulesObj() as $changeModule)
		{
			$moduleName = $changeModule->getName();
			if ($moduleName == "website" || $moduleName == "generic")
			{
				continue;
			}
			$stylesheetId = 'modules.' . $moduleName . '.frontoffice';
			$stylesheetPath = $ss->getSourceLocation($stylesheetId);
			if ($stylesheetPath)
			{
				$stylesheetArray[] = $stylesheetId;
			}
		}
		return $stylesheetArray;
	}

	/**
	 * @param String $path
	 */
	private function buildGlobalPrintStylesheetAtPath($path)
	{
		f_util_FileUtils::mkdir(dirname($path));
		$ss = StyleService::getInstance();

		$fh = fopen($path, 'w');
		$this->appendStylesheetContent($fh, 'modules.generic.print', K::HTML);
		$this->appendStylesheetContent($fh, 'modules.website.print', K::HTML);

		foreach (ModuleService::getInstance()->getModulesObj() as $changeModule)
		{
			$moduleName = $changeModule->getName();
			if ($moduleName == "website" || $moduleName == "generic")
			{
				continue;
			}
			$stylesheetId = 'modules.' . $moduleName . '.print';
			$stylesheetPath = $ss->getSourceLocation($stylesheetId);
			if ($stylesheetPath)
			{
				$this->appendStylesheetContent($fh, $stylesheetId, K::HTML);
			}
		}
		fclose($fh);
	}



	/**
	 * @param Ressource $fileHandle
	 * @param String $styleName
	 * @param String $mimeContentType
	 */
	private function appendStylesheetContent($fileHandle, $styleName, $mimeContentType)
	{
		$ss = StyleService::getInstance();
		$engine = $ss->getFullEngineName($mimeContentType);
		$content = StyleService::getInstance()->getCSS($styleName, $engine, $this->getSkin());
		if ($content !== null)
		{
			fwrite($fileHandle, $content);
		}
	}
}
