<?php
class website_urlrewriting_RulesParser extends BaseService
{
	/**
	 * Unique instance of website_urlrewriting_RulesParser.
	 *
	 * @var website_urlrewriting_RulesParser
	 */
	private static $instance;

	/**
	 * Returns the unique instance of website_urlrewriting_RulesParser.
	 *
	 * @return website_urlrewriting_RulesParser
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
	 * Path to the compiled rules file.
	 *
	 * @var string
	 */
	private $compiledRulesFile = null;


	protected function __construct()
	{
		$this->compiledRulesFile = f_util_FileUtils::buildChangeBuildPath('urlrewriting_rules.php');
	}


	/**
	 * Returns the path to the PHP file where the rules are compiled.
	 *
	 * @return string
	 */
	public function getCachedFilePath()
	{
		return $this->compiledRulesFile;
	}


	/**
	 * Compiles all the URL rewriting rules found in this project (modules + webapp).
	 *
	 * @param booelan $force If set to true, forces the recompilation of the rules.
	 *
	 * @throws IOException When the cached file could not be created.
	 * @throws UrlRewritingException When a config file is not well-formed.
	 */
	public function compile($force = false)
	{
		if ( ! is_readable($this->compiledRulesFile) || filesize($this->compiledRulesFile) == 0 || $force === true )
		{
			$php = array();
			$php[] = "<?php\n/** Generated on " . date(DATE_RFC822) . " **/";
			$php[] = "/** See ".__CLASS__." **/\n";
			$configFiles = $this->findConfigFiles();
			foreach ($configFiles as $packageName => $fileContent)
			{
				$this->parseFile($packageName, $fileContent, $php);
			}
			$php[] = '?>';
			f_util_FileUtils::write( $this->compiledRulesFile, join(K::CRLF, $php), f_util_FileUtils::OVERRIDE );
			$serializedPath = f_util_FileUtils::buildChangeBuildPath('urlrewriting_rules.ser');
			if (file_exists($serializedPath))
			{
				unlink($serializedPath);
			}
		}
	}


	/**
	 * @param string $packageName
	 * @param string $fileContent
	 * @param array<string> $php
	 */
	private function parseFile($packageName, $fileContent, &$php)
	{
		$xml = @simplexml_load_string($fileContent);

		// Check if the XML is valid
		if ( $xml instanceof SimpleXMLElement && $xml->rules instanceof SimpleXMLElement && $xml->rules->rule instanceof SimpleXMLElement)
		{
			$php[] = "\n// Package ".$packageName.".";

			// Parse the rules
			foreach ($xml->rules->rule as $rule)
			{
				// Get and sanitize template information
				$template = strval($rule->template);
				if (strlen($template))
				{
					if ($template{0} != '/')
					{
						$template = '/' . $template;
					}
				}
				else
				{
					$template = '/';
				}
				$isDocumentRule = false;
				$isActionRule = false;
				$isTagRule = false;
				if (isset($rule['documentModel']))
				{
					$isDocumentRule = true;
					$documentModel = strval($rule['documentModel']);
					if (strpos($documentModel, '/') === false)
					{
						$documentModel = $packageName.'/'.$documentModel;
					}
					$php[] = "\$rule = new website_lib_urlrewriting_DocumentModelRule(";
					$php[] = "\t// package\n\t'".$packageName."',\n\t// URL template\n\t'" . $template . "',\n\t// Document Model\n\t'".$documentModel."',\n\t// View mode\n\t'".strval($rule['viewMode'])."',\n\tarray\t(";
				}
				else if (isset($rule['redirection']))
				{
					$isActionRule = true;
					list($module, $action) = explode('/', strval($rule['redirection']));
					$php[] = "\$rule = new website_lib_urlrewriting_ModuleActionRule(";
					$php[] = "\t// package\n\t'".$packageName."',\n\t// URL template\n\t'" . $template . "',\n\t// Module\n\t'".$module."',\n\t// Action\n\t'".$action."',\n\tarray\t(";
				}
				else if (isset($rule['pageTag']))
				{
					if ($rule->parameters instanceof SimpleXMLElement && $rule->parameters->parameter instanceof SimpleXMLElement)
					{
						$isTagRule = true;
						$php[] = "\$rule = new website_lib_urlrewriting_TaggedPageRule(";
						$php[] = "\t// package\n\t'".$packageName."',\n\t// URL template\n\t'" . $template . "',\n\t// Page tag\n\t'".strval($rule['pageTag'])."',\n\tarray\t(";
					}
					else
					{
						echo 'Tag rule with no parameter is ignored : ' . $packageName . ' ' .$template . ' -> ' .strval($rule['pageTag']) . "\n";
						continue;
					}
				}
				else
				{
					throw new UrlRewritingException('Unknown rule type in package "'.$packageName.'".');
				}

				if (isset($rule['lang']))
				{
					$lang = strval($rule['lang']);
				}
				else
				{
					$lang = null;
				}

				$parametersForwardArray = array();
				if ($rule->parameters instanceof SimpleXMLElement
					&& $rule->parameters->parameter instanceof SimpleXMLElement)
				{
					// Parse rule parameters
					foreach ($rule->parameters->parameter as $parameter)
					{
						$this->parseParameter($parameter, array(), $parametersForwardArray, $php);
					}
					// Parse rule parameters
					foreach ($rule->parameters->globalParameter as $parameter)
					{
						$this->parseParameter($parameter, array("'__parameterType' => 'global'"), $parametersForwardArray, $php);
					}
					// Parse rule parameters
					foreach ($rule->parameters->moduleParameter as $parameter)
					{
						$this->parseParameter($parameter, array("'__parameterType' => 'module'"), $parametersForwardArray, $php);
					}
				}

				if (!is_null($lang))
				{
					$php[] = "\t\t'lang' => array('value' => '" . $lang . "'),";
				}

				// close third parameter of urlrewriting_lib_Rule (array)
				$php[] = "\t\t)";
				// close new rule definition
				$php[] = "\t);";

				// manage parameters mapping
				foreach ($parametersForwardArray as $name => $forwardArray)
				{
					foreach ($forwardArray as $forward)
					{
						$php[] = "\$rule->addParameterForward('" . $name . "', '" . $forward . "');";
					}
				}

				if (isset($rule['condition']))
				{
					$php[] = "\$rule->setCondition('" . strval($rule['condition']) . "');";
				}
				if ($isDocumentRule)
				{
					$php[] = "\$this->addDocumentRule(\$rule);";
				}
				else if ($isActionRule)
				{
					$php[] = "\$this->addActionRule(\$rule);";
				}
				else if ($isTagRule)
				{
					$php[] = "\$this->addTagRule(\$rule);";
				}
			}
		}
		else
		{
			// XML file is present but invalid: throw an Exception
			throw new UrlRewritingException("Invalid URL rewriting definition for package '".$packageName."': XML not well-formed.");
		}
	}

	/**
	 * @param Array $initialAttr
	 * @param String[] $initialAttr
	 * @param Array $parametersForwardArray
	 * @param String[] $php
	 */
	private function parseParameter($parameter, $initialAttr = array(), &$parametersForwardArray, &$php)
	{
		$parameterName = strval($parameter['name']);

		if (isset($parameter['forwardTo']))
		{
			if (!isset($parametersForwardArray[$parameterName]))
			{
				$parametersForwardArray[$parameterName] = array();
			}
			$parametersForwardArray[$parameterName][] = strval($parameter['forwardTo']);
		}
		else
		{
			// Include all the parameter's attributes except the
			// 'name' attribute, which is the key in the associative
			// table.
			$attrs = $initialAttr;
			foreach ($parameter->attributes() as $name => $value)
			{
				if ($name != 'name')
				{
					$attrs[] = "'" . $name . "' => '" . $value . "'";
				}
			}
			$php[] = "\t\t'" . $parameterName . "' => array(" . join(", ", $attrs) . "),";
		}
	}

	/**
	 * @return array<String:packageName => String:fileContent>
	 */
	private function findConfigFiles()
	{
		$urlRewritingRulesFinderClass = Framework::getConfiguration("modules/website/urlrewritingRuleFinder", false);
		if ($urlRewritingRulesFinderClass === false)
		{
			$fileContent = array();
			
			$modules = ModuleService::getInstance()->getModules();
			foreach ($modules as $module)
			{
				$filePath = $this->getDefinitionFilePathByPackage($module);
				if ($filePath)
				{
					$fileContent[$module] = f_util_FileUtils::read($filePath);
				}
			}
			$filePath = CHANGE_CONFIG_DIR . DIRECTORY_SEPARATOR . 'urlrewriting.xml';
			if (is_readable($filePath))
			{
				$fileContent['webapp'] = f_util_FileUtils::read($filePath);
			}
			return $fileContent;
		}
		else
		{
			$urlRewritingRulesFinder = new $urlRewritingRulesFinderClass();
			return $urlRewritingRulesFinder->getConfigFiles();	
		}
	}
	
	/**
	 * @param String $package
	 * @return String
	 */
	public function getDefinitionFilePathByPackage($package)
	{
		return FileResolver::getInstance()->setPackageName($package)->getPath('/config/urlrewriting.xml');
	}
}