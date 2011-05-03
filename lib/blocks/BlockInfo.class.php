<?php
class block_BlockInfo
{
	private static $DEFAULT = array('label' => null, 'icon'=> null, 
		'editable' => false, 'hidden' => false, 'dashboard' => false, 
		'afterAll' => false, 'beforeAll' => false);

	/**
	 * @var array
	 */
	private $attributes;
	
	/**
	 * @var block_BlockPropertyInfo[]
	 */
	private $parametersInfoArray = array();
	
	/**
	 * @var array
	 */
	protected $titleMetas = array();
	
	/**
	 * @var array
	 */
	protected $descriptionMetas = array();
	
	/**
	 * @var array
	 */
	protected $keywordsMetas = array();
	
	/**
	 * @var array
	 */
	protected $metas = array();

	protected function __construct($infoArray = array())
	{
		$this->attributes = array_merge(self::$DEFAULT, $infoArray);
	}
	
	protected function addNewBlockPropertyInfo($propertyInfoArray)
	{
		$parameterInfo = new block_BlockPropertyInfo($propertyInfoArray);
		$this->addParameterInfo($parameterInfo);
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->attributes['type'];
	}
	
	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->getType();
	}
	
	/**
	 * @return boolean
	 */
	function getEditable()
	{
		return $this->attributes['editable'];
	}

	/**
	 * @return string
	 */
	public function getLabel()
	{
		return $this->attributes['label'];
	}

	/**
	 * @return string
	 */
	public function getIcon()
	{
		return $this->attributes['icon'];
	}


	/**
	 * @return boolean
	 */
	public function getDashboard()
	{
		return $this->attributes['dashboard'];
	}

	/**
	 * @return boolean
	 */
	public function getHidden()
	{
		return $this->attributes['hidden'];
	}
	
	/**
	 * @return boolean
	 */
	public function isHidden()
	{
		return $this->getHidden();
	}
	
	/**
	 * @return string
	 */
	public function getSection()
	{
		return $this->attributes['section'];
	}

	/**
	 * @return string
	 */
	public function getDropModels()
	{
		return $this->attributes['dropModels'];
	}
	
	/**
	 * @return string
	 */
	public function getDropModelArray()
	{
		$string = $this->getDropModels();
		if (!empty($string))
		{
			return explode(',', str_replace(' ', '', $string));
		}
		return array();
	}	
	
	/**
	 * @return string
	 */
	public final function getModule()
	{
		list(,$module,) = explode('_', $this->getType());
		return $module;
	}
	
	
	
	/**
	 * @return string
	 */
	public function getRequestModule()
	{
		return $this->attributes['requestModule'];
	}
	
	/**
	 * @return string
	 */
	public function getTemplateModule()
	{
		return $this->attributes['templateModule'];
	}
	
	/**
	 * @return string
	 */
	public function getPhpBlockClass()
	{
		return $this->attributes['phpBlockClass'];
	}
	
	public function getInjectedBy()
	{
		return isset($this->attributes['injectedBy']) ? $this->attributes['injectedBy'] : null;
	}
	
	/**
	 * @return boolean
	 */
	public function getBeforeAll()
	{
		return $this->attributes['beforeAll'];
	}	
	
	/**
	 * @return boolean
	 */
	public function getAfterAll()
	{
		return $this->attributes['afterAll'];
	}
	
	/**
	 * @return boolean
	 */
	public function hasContent()
	{
		return false;
	}
	
	/**
	 * @return Array<block_BlockPropertyInfo>
	 */
	public final function getParametersInfoArray()
	{
		return $this->parametersInfoArray;
	}

	/**
	 * @param String $name
	 * @return block_BlockPropertyInfo
	 */
	public final function getParameterInfo($name)
	{
		return $this->hasParameterInfo($name) ? $this->parametersInfoArray[$name] : null;
	}

	/**
	 * @param String $name
	 * @return Boolean
	 */
	public final function hasParameterInfo($name)
	{
		return isset($this->parametersInfoArray[$name]);
	}

	/**
	 * @param block_BlockPropertyInfo $parameterInfo
	 */
	public final function addParameterInfo($parameterInfo)
	{
		$this->parametersInfoArray[$parameterInfo->getName()] = $parameterInfo;
	}

	/**
	 * @return Boolean
	 */
	public function hasMeta()
	{
		return count($this->metas) > 0;
	}

	function getTitleMetas()
	{
		return $this->titleMetas;
	}

	function getDescriptionMetas()
	{
		return $this->descriptionMetas;
	}

	function getKeywordsMetas()
	{
		return $this->keywordsMetas;
	}

	function getAllMetas()
	{
		return $this->metas;
	}

	/**
	 * @param String $name
	 * @param String $value
	 * @return block_BlockInfo
	 */
	public function setAttribute($name, $value)
	{
		$this->attributes[$name] = $value;
		return $this;
	}

	/**
	 * @param String $name
	 * @return Boolean
	 */
	public function hasAttribute($name)
	{
		return isset($this->attributes[$name]);
	}

	/**
	 * @param String $name
	 * @return String
	 */
	public function getAttribute($name)
	{
		return isset($this->attributes[$name]) ? $this->attributes[$name] : null;
	}

	/**
	 * @return Array<String=>String>
	 */
	public function getAttributes()
	{
		return $this->attributes;
	}

	/**
	 * @return Array<block_BlockPropertyInfo>
	 */
	public final function getPropertyGridParameters()
	{
		$result = array();
		foreach ($this->parametersInfoArray as $parameter)
		{
			if (!$parameter->getHidden())
			{
				$result[] = $parameter;
			}
		}
		return $result;
	}

	/**
	 * @return Boolean
	 */
	public final function hasPropertyGrid()
	{
		return count($this->getPropertyGridParameters()) > 0;
	}
}