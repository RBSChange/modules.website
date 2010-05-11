<?php
abstract class website_lib_urlrewriting_Rule
{
	const PARAMETER_DEFAULT_FORMAT = '[0-9a-zA-Z\.\-_]+';
	const PARAMETER_ID_DEFAULT_FORMAT = '[0-9]+';
	const PARAMETER_LANG_DEFAULT_FORMAT = '[a-z]{2}';


	/**
	 * Module of the rule;
	 *
	 * @var string
	 */
	protected $m_parentModule;


	/**
	 * Package of the rule;
	 *
	 * @var string
	 */
	protected $m_package;


	/**
	 * URL template of the rule.
	 *
	 * @var string
	 */
	protected $m_template;


	/**
	 * Parameters in the rule.
	 *
	 * @var array
	 */
	protected $m_parameters;


	/**
	 * Internal regular expression representing the URL template.
	 *
	 * @var string
	 */
	protected $m_regExp;


	/**
	 * The last found matches when checking a URL with this rule.
	 *
	 * @var array
	 */
	protected $m_lastMatches = array();


	/**
	 * Parameters found in the template.
	 *
	 * @var array
	 */
	protected $m_templateParameters = array();


	/**
	 * Does the rule concern a directory-like URL, ending with a slash?
	 *
	 * @var boolean
	 */
	protected $m_isDir = false;


	/**
	 * The attached language.
	 *
	 * @var string
	 */
	protected $m_lang = null;


	/**
	 * The suffix used for URLs generated from this rule.
	 *
	 * @var string
	 */
	protected $m_suffix = null;


	/**
	 * @var boolean
	 */
	protected $m_movedPermanently = false;


	/**
	 * @var string
	 */
	protected $m_redirectionUrl = null;


	/**
	 * @var array
	 */
	protected $m_parametersForwardArray = array();


	/**
	 * @var String
	 */
	protected $m_condition = null;


	/**
	 * Returns the unique ID of the rule.
	 *
	 * @return string
	 */
	abstract public function getUniqueId();


	/**
	 * Builds the rule object.
	 *
	 * @param string $package Package.
	 * @param string $name Name of the rule.
	 * @param string $template Template of the rule.
	 * @param array $parameters The parameters.
	 */
	public final function initialize($package, $template, $parameters)
	{
		$this->m_package = trim($package);
		if (substr($this->m_package, 0, 8) == 'modules_')
		{
			$this->m_parentModule = substr($this->m_package, 8);
		}
		$this->m_template = trim($template);
		if ($this->m_template{0} != '/')
		{
			$this->m_template = '/' . $this->m_template;
		}
		if (substr($this->m_template, -1) == '/')
		{
			$this->m_isDir = true;
		}
		else
		{
			$matches = array();
			if (preg_match('/\.([a-z0-9]{2,})$/', $this->m_template, $matches))
			{
				$this->m_suffix = $matches[1];
			}
		}
		if ( is_array($parameters) )
		{
			$this->m_parameters = $parameters;
		}
		else
		{
			$this->m_parameters = array();
		}
		if (isset($this->m_parameters['lang']['value']))
		{
			$this->m_lang = $this->m_parameters['lang']['value'];
			unset($this->m_parameters['lang']);
		}
		$this->buildRegExp();
	}


	/**
	 * Builds the regular expression to match against URLs to check.
	 */
	private function buildRegExp()
	{
		$search = array('.', '(', ')', '{', '}', '[', ']');
		$replace = array('\\.', '\(', '\)', '\{', '\}', '\[', '\]');
		$this->m_regExp = str_replace($search, $replace, $this->m_template);
		$matches = array();
		if (preg_match_all('/\$([a-z]+)/i', $this->m_regExp, $matches))
		{
			$parametersName = $matches[1];
			$this->m_templateParameters = array();
			foreach ($parametersName as $name)
			{
				$this->m_templateParameters[] = $name;
				if (isset($this->m_parameters[$name]['format']))
				{
					$format = $this->m_parameters[$name]['format'];
				}
				else
				{
					if ($name == 'id')
					{
						$format = self::PARAMETER_ID_DEFAULT_FORMAT ;
					}
					else if ($name == 'lang')
					{
						$format = self::PARAMETER_LANG_DEFAULT_FORMAT ;
					}
					else
					{
						$format = self::PARAMETER_DEFAULT_FORMAT;
					}
				}
				if (!isset($this->m_parameters[$name]))
				{
					$this->m_parameters[$name] = array('format' => $format);
				}
				$this->m_regExp = str_replace('$'.$name, '('.$format.')', $this->m_regExp);
			}
		}
		if (substr($this->m_regExp, -1) == '/')
		{
			$this->m_regExp .= '?';
		}
	}


	/**
	 * Checks if the $url matches the rule.
	 *
	 * @param string $url The URL to check.
	 * @return boolean true if the URL matches the rule, false otherwise.
	 */
	public final function match($url)
	{
		if (website_UrlRewritingService::hasSuffix($url) && $this->isDirectoryLike())
		{
			return false;
		}
		if (!$this->hasSuffix())
		{
			$url = website_UrlRewritingService::getInstance()->removeSuffix($url);
		}
		$regExp = '!^'.$this->m_regExp.'$!i';

		$m = null;
		if (preg_match($regExp, $url, $m) )
		{
			if (Framework::isDebugEnabled() )
			{
				Framework::debug(__METHOD__ . " Matching '".$url."' with '".$regExp."'");
			}
			$matches = array();
			foreach ($this->m_templateParameters as $index => $name)
			{
				if ( isset($m[$index+1]) )
				{
					$matches[$name] = f_util_Convert::fixDataType($m[$index+1]);
				}
			}
			// append default-valued parameters
			foreach ($this->m_parameters as $name => $parameter)
			{
				if (!isset($matches[$name]) && isset($parameter['value']))
				{
					$matches[$name] = f_util_Convert::fixDataType($parameter['value']);
				}
			}

			$this->m_lastMatches = $matches;
		}
		else
		{
			$this->m_lastMatches = null;
		}
		return is_null($this->m_lastMatches) ? false : true;
	}


	/**
	 * Returns the parameters of the rule.
	 *
	 * @return array
	 */
	public final function getParameters()
	{
		return $this->m_parameters;
	}


	/**
	 * Returns the template of the rule.
	 *
	 * @return string
	 */
	public final function getTemplate()
	{
		return $this->m_template;
	}


	/**
	 * Indicates whether the rule is a directory like rule (ends with a '/').
	 *
	 * @return boolean
	 */
	public final function isDirectoryLike()
	{
		return $this->m_isDir;
	}


	/**
	 * Returns the matches found during the last URL check.
	 *
	 * @return array The matches found during the last checking process.
	 */
	public final function getMatches()
	{
		return $this->m_lastMatches;
	}


	/**
	 * Returns the module name to which the rule is attached.
	 *
	 * @return string
	 */
	public final function getParentModule()
	{
		return $this->m_parentModule;
	}


	/**
	 * Returns the package name to which the rule is attached.
	 *
	 * @return string
	 */
	public final function getPackage()
	{
		return $this->m_package;
	}

	/**
	 * @return string
	 */
	public function getDefinedLang()
	{
		return $this->m_lang;
	}
	
	/**
	 * Returns the language associated to this rule.
	 *
	 * @return string
	 */
	public final function getLang()
	{
		if ($this->m_lang !== null)
		{
			return $this->m_lang;
		}
		elseif ($this->m_lastMatches !== null && isset($this->m_lastMatches["lang"]))
		{
			return $this->m_lastMatches["lang"];
		}
		return null;
	}


	/**
	 * Indicates whether the rule is localized or not.
	 * A rule is localized when it holds a language information: a $lang
	 * parameter may appear in the URL template or the rule is defined
	 * specifically for a language, with the "lang" attribute. In those two
	 * cases, the rule is localized.
	 *
	 * @return boolean
	 */
	public final function isLocalized()
	{
		return (!is_null($this->m_lang)) || in_array('lang', $this->m_templateParameters);
	}


	/**
	 * Returns the URL built accordingly to the rule template and using the
	 * given parameters.
	 *
	 * @param array $parameters Associative array of parameters.
	 * @return string URL
	 */
	public function getUrl(&$parameters = array())
	{
		// check if all the template parameters are given
		$diffs = array_diff($this->m_templateParameters, array_keys($parameters));
		if (count($diffs) > 0)
		{
			throw new UrlRewritingException("Missing parameters: ".join(", ", $diffs));
		}
		$result = $this->m_template;
		foreach ($parameters as $name => $parameter)
		{
			$urlLabel = website_UrlRewritingService::getUrlLabel($parameter);
			if (strpos($result, '$'.$name) !== false)
			{
				$result = str_replace('$'.$name, $urlLabel, $result);
				unset($parameters[$name]);
			}
		}
		return $result;
	}


	/**
	 * Sets the suffix to use by this rule.
	 *
	 * @param string $suffix
	 */
	public final function setSuffix($suffix)
	{
		$this->m_suffix = $suffix;
	}


	/**
	 * Returns the suffix used by this rule.
	 *
	 * @return string
	 */
	public final function getSuffix()
	{
		return $this->m_suffix;
	}


	/**
	 * Indicates whether the rule holds a suffix information or not.
	 *
	 * @return boolean
	 */
	public final function hasSuffix()
	{
		return ! is_null($this->m_suffix);
	}


	/**
	 * Returns a string representation of the rule.
	 *
	 * @return string
	 */
	public final function __toString()
	{
		return $this->toString();
	}


	/**
	 * @param string $url
	 */
	public function setRedirectionUrl($url)
	{
		$this->m_redirectionUrl = $url;
	}


	/**
	 * @return string
	 */
	public function getRedirectionUrl()
	{
		return $this->m_redirectionUrl;
	}

	/**
	 * @param boolean $permanently
	 */
	public function setMovedPermanently($permanently)
	{
		$this->m_movedPermanently = $permanently;
	}

	/**
	 * @return boolean
	 */
	public function hasMovedPermanently()
	{
		return $this->m_movedPermanently;
	}


	/**
	 * Returns a string representation of the rule.
	 *
	 * @see __toString()
	 *
	 * @return string
	 */
	public function toString()
	{
		return 'UrlRewritingRule('.$this->getUniqueId().') '.$this->m_template;
	}


	/**
	 * @param String $parameter
	 * @param String $destination
	 */
	public function addParameterForward($parameter, $destination)
	{
		if (!isset($this->m_parametersForwardArray[$parameter]))
		{
			$this->m_parametersForwardArray[$parameter] = array();
		}
		$this->m_parametersForwardArray[$parameter][] = $destination;
	}


	/**
	 * @return Array<String=>Array>
	 */
	public function getParameterForwardArray()
	{
		return $this->m_parametersForwardArray;
	}


	/**
	 * Sets the condition for this rule.
	 *
	 * @param String $condition
	 */
	public function setCondition($condition)
	{
		$condition = trim($condition);
		if ( ! preg_match('/^\!?[a-z]+\(\)$/i', $condition) )
		{
			throw new UrlRewritingException("Malformed rule: condition must be a method name terminated by '()'; condition is: '$condition'.");
		}
		$this->m_condition = $condition;
	}


	/**
	 * @return String
	 */
	public function getCondition()
	{
		return $this->m_condition;
	}


	/**
	 * @return Boolean
	 */
	public function hasCondition()
	{
		return ! is_null($this->getCondition());
	}


	/**
	 * @param f_persistentdocument_PersistentDocument $document
	 */
	public function checkCondition($document)
	{
		$cond = $this->getCondition();
		if ($cond{0} == '!')
		{
			$inverse = true;
			$start = 1;
		}
		else
		{
			$inverse = false;
			$start = 0;
		}
		$method = substr($cond, $start, -2); // remove ending '()'
		if ( ! f_util_ClassUtils::methodExists($document, $method) )
		{
			throw new UrlRewritingException("Unable to call condition method '$method' on document '".$document->__toString()."'.");
		}

		$result = f_util_ClassUtils::callMethodOn($document, $method);
		return $inverse ? ! $result : $result;
	}
}
