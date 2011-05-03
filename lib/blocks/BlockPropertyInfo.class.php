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
	 * @param String $value
	 * @return block_BlockPropertyInfo
	 */
	public function setLabel($value)
	{
		$this->m_label = $value;
		return $this;
	}

	/**
	 * @return String
	 */
	public function getLabel()
	{
		return $this->m_label;
	}

	/**
	 * @param String $value
	 * @return block_BlockPropertyInfo
	 */
	public function setHelpText($value)
	{
		$this->m_helpText = $value;
		return $this;
	}

	/**
	 * @return Boolean
	 */
	public function hasHelpText()
	{
		return ! is_null($this->m_helpText);
	}

	/**
	 * @return String
	 */
	public function getHelpText()
	{
		return $this->m_helpText;
	}

	/**
	 * @param String $value
	 * @return block_BlockPropertyInfo
	 */
	public function setListId($value)
	{
		$this->m_listId = $value;
		return $this;
	}

	/**
	 * @return Boolean
	 */
	public function hasListId()
	{
		return ! is_null($this->m_listId);
	}

	/**
	 * @return String
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
		if ( $bool && ! $this->isRequired() )
		{
			$this->setMinOccurs(1);
		}
		else if ( ! $bool )
		{
			$this->setMinOccurs(0);
		}
		return $this;
	}

	/**
	 * @return Boolean
	 */
	public function isRequired()
	{
		return $this->getMinOccurs() > 0;
	}
	
	/**
	 * @param Boolean $bool
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
	 * @return Boolean
	 */
	public function hasDefaultValue()
	{
		return !is_null($this->getDefaultValue());
	}
	
	
	/**
	 * @param String $name
	 * @param String $value
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