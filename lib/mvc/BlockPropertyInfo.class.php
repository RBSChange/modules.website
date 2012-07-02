<?php
class block_BlockPropertyInfo extends PropertyInfo
{
	private static $DEFAULT = array('name'=> null, 'type' => 'String', 'document-type' => null, 'constraints' => null, 
		'min-occurs' => 0, 'max-occurs' => 1, 'default-value' => null, 'from-list' => null,
		'label' => null, 'helptext' => null, 'hidden' => false);
	/**
	 * @var string
	 */
	private $m_label = null;

	/**
	 * @var string
	 */
	private $m_helpText = null;
	
	/**
	 * @var boolean
	 */
	private $m_hidden = false;	

	/**
	 * @var array
	 */
	private $m_extendedAttributes = array();
	
	public function __construct($propertyInfoArray)
	{
		$p = array_merge(self::$DEFAULT, $propertyInfoArray);
		$name = $p['name'];
		$type = $p['type'];
		parent::__construct($name, $type);
		$maxOccurs = intval($p['max-occurs']);
		if (abs($maxOccurs) != 1) {$this->setMaxOccurs($maxOccurs);}
		$minOccurs = intval($p['min-occurs']);
		if ($minOccurs != 0) {$this->setMinOccurs($minOccurs);}
		$defaultValue = $p['default-value'];
		if ($defaultValue !== null) {$this->setDefaultValue($defaultValue);}		
		$fromList = $p['from-list'];
		if ($fromList !== null) {$this->setFromList($fromList);}
		
		if (isset($p['hidden'])) {$this->setHidden($p['hidden']);}
		if (isset($p['helptext'])) {$this->setHelpText($p['helptext']);}
		if (isset($p['constraints'])) {$this->setConstraints($p['constraints']);}
		
		$this->m_extendedAttributes = array_diff($propertyInfoArray, self::$DEFAULT);
	}
			
	/**
	 * @param string $value
	 * @return block_BlockPropertyInfo
	 */
	public function setLabel($value)
	{
		$this->m_label = $value;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getLabel()
	{
		return $this->m_label;
	}

	/**
	 * @param string $value
	 * @return block_BlockPropertyInfo
	 */
	public function setHelpText($value)
	{
		$this->m_helpText = $value;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function hasHelpText()
	{
		return $this->m_helpText !== null;
	}

	/**
	 * @return string
	 */
	public function getHelpText()
	{
		return $this->m_helpText;
	}

	/**
	 * @return boolean
	 */
	public function hasListId()
	{
		return $this->getFromList() !== null;
	}

	/**
	 * @return string
	 */
	public function getListId()
	{
		return $this->getFromList();
	}

	/**
	 * @param boolean
	 * @return block_BlockPropertyInfo
	 */
	public function setRequired($bool)
	{
		if ($bool && !$this->isRequired())
		{
			$this->setMinOccurs(1);
		}
		else if (!$bool)
		{
			$this->setMinOccurs(0);
		}
		return $this;
	}
	
	/**
	 * @param boolean $bool
	 * @return block_BlockPropertyInfo
	 */
	public function setHidden($bool)
	{
		$this->m_hidden = f_util_Convert::toBoolean($bool);
		return $this;
	}	
	
	public function getHidden()
	{
		return $this->m_hidden;
	}

	/**
	 * @return boolean
	 */
	public function hasDefaultValue()
	{
		return $this->getDefaultValue() !== null;
	}
	
	
	/**
	 * @param string $name
	 * @param string $value
	 */
	public function setExtendedAttribute($name, $value)
	{
		$this->m_extendedAttributes[$name] = $value;
		return $this;
	}
	
	/**
	 * @return Array
	 */
	public function getExtendedAttributeArray()
	{
		return $this->m_extendedAttributes;
	}
}