<?php
class block_BlockInfo
{
	/**
	 * @var String
	 */
	private $label = null;

	/**
	 * @var String
	 */
	private $id = null;

	/**
	 * @var String
	 */
	private $icon = null;

	/**
	 * @var String
	 */
	private $image = null;

	/**
	 * @var String
	 */
	private $color = null;

	/**
	 * @var String
	 */
	private $type = null;

	/**
	 * @var String
	 */
	private $ref = null;

	/**
	 * @var String
	 */
	private $attributes = array();

	/**
	 * @var Boolean
	 */
	private $hidden = null;

	/**
	 * @var Boolean
	 */
	private $requiresNewEditor = false;


	/**
	 * @var Boolean
	 */
	private $dashboard = false;

	/**
	 * @var Array<block_BlockPropertyInfo>
	 */
	protected $parametersInfoArray = array();

	/**
	 * @var String
	 */
	protected $content = null;
	
	/**
	 * @var Boolean
	 */
	protected $editable = false;

	/**
	 * @param String $value
	 * @return block_BlockInfo
	 */
	public function setId($value)
	{
		$this->id = $value;
		return $this;
	}

	/**
	 * @return String
	 */
	public function getId()
	{
		return $this->id;
	}
	
	/**
	 * @param Boolean $editable
	 * @return block_BlockInfo
	 */
	function setEditable($editable)
	{
		$this->editable = $editable;
		return $this;
	}
	
	/**
	 * @return Boolean
	 */
	function getEditable()
	{
		return $this->editable;
	}

	/**
	 * @param String $value
	 * @return block_BlockInfo
	 */
	public function setLabel($value)
	{
		$this->label = $value;
		return $this;
	}

	/**
	 * @return String
	 */
	public function getLabel()
	{
		return $this->label;
	}

	/**
	 * @param String $value
	 * @return block_BlockInfo
	 */
	public function setIcon($value)
	{
		$this->icon = $value;
		return $this;
	}

	/**
	 * @return String
	 */
	public function getIcon()
	{
		return $this->icon;
	}

	/**
	 * @param String $value
	 * @return block_BlockInfo
	 */
	public function setImage($value)
	{
		$this->image = $value;
		return $this;
	}

	/**
	 * @return String
	 */
	public function getImage()
	{
		return $this->image;
	}

	/**
	 * @param String $value
	 * @return block_BlockInfo
	 */
	public function setColor($value)
	{
		$this->color = $value;
		return $this;
	}

	/**
	 * @return String
	 */
	public function getColor()
	{
		return $this->color;
	}

	/**
	 * @param String $value
	 * @return block_BlockInfo
	 */
	public function setType($value)
	{
		$this->type = $value;
		return $this;
	}

	/**
	 * @return String
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param String $value
	 * @return block_BlockInfo
	 */
	public function setRef($value)
	{
		$this->ref = $value;
		return $this;
	}

	/**
	 * @return String
	 */
	public function getRef()
	{
		return $this->ref;
	}

	/**
	 * @param String $value
	 * @return block_BlockInfo
	 */
	public function setContent($value)
	{
		$this->content = $value;
		return $this;
	}

	/**
	 * @return String
	 */
	public function hasContent()
	{
		return ! is_null($this->content);
	}

	/**
	 * @return String
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * @param  Boolean
	 * @return block_BlockPropertyInfo
	 */
	public function setRequiresNewEditor($bool)
	{
		$this->requiresNewEditor = $bool;
		return $this;
	}

	/**
	 * @return Boolean
	 */
	public function requiresNewEditor()
	{
		return $this->requiresNewEditor;
	}

	/**
	 * @param  Boolean
	 * @return block_BlockPropertyInfo
	 */
	public function setDashboard($bool)
	{
		$this->dashboard = $bool;
		return $this;
	}

	/**
	 * @return Boolean
	 */
	public function getDashboard()
	{
		return $this->dashboard;
	}

	/**
	 * @param  Boolean
	 * @return block_BlockPropertyInfo
	 */
	public function setHidden($bool)
	{
		$this->hidden = $bool;
		return $this;
	}

	/**
	 * @return Boolean
	 */
	public function isHidden()
	{
		return $this->hidden;
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
	 * @return String
	 */
	public final function getModule()
	{
		return block_BlockService::getInstance()->getDeclaringModuleForBlock($this->getType());
	}

	/**
	 * @return Boolean
	 */
	public final function isContent()
	{
		return $this->getType() == 'content';
	}

	protected $titleMetas = array();
	protected $descriptionMetas = array();
	protected $keywordsMetas = array();
	protected $metas = array();

	/**
	 * @param String $metaName
	 * @param String $allow comma separated values. Default value : "title,description,keywords"
	 */
	public function addMeta($metaName, $allow)
	{
		$allowArray = null;
		if ($allow !== null)
		{
			$allowArray = explode(',', $allow);
		}
		$this->metas[] = $metaName;
		if ($allowArray === null || in_array('title', $allowArray))
		{
			$this->titleMetas[] = $metaName;
		}
		if ($allowArray === null || in_array('description', $allowArray))
		{
			$this->descriptionMetas[] = $metaName;
		}
		if ($allowArray === null || in_array('keywords', $allowArray))
		{
			$this->keywordsMetas[] = $metaName;
		}
	}

	/**
	 * @return Boolean
	 */
	public function hasMeta()
	{
		return f_util_ArrayUtils::isNotEmpty($this->metas);
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
		return f_util_ArrayUtils::isNotEmpty($this->getPropertyGridParameters());
	}
}