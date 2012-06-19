<?php
class block_BlockPropertyInfo extends PropertyInfo
{
	private static $DEFAULT = array('name'=> null, 'type' => 'String',  
		'min-occurs' => 0, 'max-occurs' => 1, 'default-value' => null, 'from-list' => null,
		'label' => null, 'helptext' => null, 'hidden' => false);
	/**
	 * @var String
	 */
	private $m_label = null;

	/**
	 * @var String
	 */
	private $m_helpText = null;

	/**
	 * @var String
	 */
	private $m_listId = null;
	
	/**
	 * @var Boolean
	 */
	private $m_hidden = false;	

	/**
	 * @var Array
	 */
	private $m_extendedAttributes = array();
	
	public function __construct($propertyInfoArray)
	{
		$p = array_merge(self::$DEFAULT, $propertyInfoArray);
		$name = $p['name'];
		$type = $p['type'];
		$minOccurs = intval($p['min-occurs']);
		$maxOccurs = intval($p['max-occurs']);
		$isDocument = strpos($type, 'modules_') === 0;
		$isArray = $isDocument && $maxOccurs != 1;
		$defaultValue = $p['default-value'];
		$fromList = $p['from-list'];
		$constraints = '';
		
		parent::__construct($name, $type, $minOccurs, $maxOccurs, '', '', false, false, false, 
			$isArray, $isDocument, $defaultValue, $constraints, 
			false, false, false, $fromList);
	
		$this->setLabel($p['label']);
		$this->setHelpText($p['helptext']);
		$this->setListId($fromList);
		$this->setHidden($p['hidden']);
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
	 * @param string $value
	 * @return block_BlockPropertyInfo
	 */
	public function setListId($value)
	{
		$this->m_listId = $value;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function hasListId()
	{
		return $this->m_listId !== null;
	}

	/**
	 * @return string
	 */
	public function getListId()
	{
		return $this->m_listId;
	}

	/**
	 * @param  Boolean
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
	 * @return boolean
	 */
	public function isRequired()
	{
		return $this->getMinOccurs() > 0;
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