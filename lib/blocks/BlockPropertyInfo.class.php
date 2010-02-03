<?php
class block_BlockPropertyInfo extends PropertyInfo
{
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
	
	/**
	 * @var FormPropertyInfo
	 */
	private $m_formPropertyInfo = null;
	
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
		return ! is_null($this->getDefaultValue());
	}
	
	public function getPhpGetter()
	{
		return 'get'.ucfirst($this->getName());
	}
	
	/**
	 * @return FormPropertyInfo
	 */
	public function getFormPropertyInfo()
	{
		if ($this->m_formPropertyInfo === null)
		{
			$display = $this->getHidden() ? "hidden" : "edit";
			if (isset($this->m_extendedAttributes['control-type']))
			{
				$controlType = $this->m_extendedAttributes['control-type'];
				unset($this->m_extendedAttributes['control-type']);
			}
			else 
			{
				$controlType = $this->getType();
			}
			$this->m_formPropertyInfo = new FormPropertyInfo($this->getName(), $controlType, $display, $this->isRequired(), $this->getLabel(), $this->m_extendedAttributes);
		}
		return $this->m_formPropertyInfo;
	}
	
	/**
	 * @param FormPropertyInfo $formPropertyInfo
	 */
	public function setFormPropertyInfo($formPropertyInfo)
	{
		$this->m_formPropertyInfo = $formPropertyInfo;
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