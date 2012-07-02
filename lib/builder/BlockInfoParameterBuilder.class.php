<?php
class website_BlockInfoParameterBuilder
{
	private $propertyInfoArray;

	public function __construct($propertyInfoArray)
	{
		$this->propertyInfoArray = $propertyInfoArray;
		if (!isset($this->propertyInfoArray['type']))
		{
			$this->propertyInfoArray['type'] = 'String';
		}
	}

	public function getName()
	{
		return $this->propertyInfoArray['name'];
	}

	public function getType()
	{
		return $this->propertyInfoArray['type'];
	}
	
	public function getDocumentType()
	{
		return isset($this->propertyInfoArray['document-type']) ? $this->propertyInfoArray['document-type'] : null;
	}

	public function getPhpGetter()
	{
		return 'get' . ucfirst($this->getName());
	}

	public function hasDefaultValue()
	{
		return isset($this->propertyInfoArray['default-value']);
	}

	public function getDefaultValue()
	{
		return isset($this->propertyInfoArray['default-value']) ? $this->propertyInfoArray['default-value'] : null;
	}

	public function isDocument()
	{
		return in_array($this->getType(), array('Document', 'DocumentArray'));
	}

	public function isArray()
	{
		return $this->getType() == 'DocumentArray';
	}

	public function getVarExportInfo()
	{
		return var_export($this->propertyInfoArray, true);
	}

	/**
	 * @return string
	 */
	public function getPHPType()
	{

		switch ($this->getType())
		{
			case 'Boolean':
				return 'boolean';
			case 'Integer':
			case 'DocumentId':
				return 'integer';
			case 'Double':
			case 'Decimal':
				return 'float';
			case 'Document':
			case 'DocumentArray':
				if ($this->getDocumentType() === f_persistentdocument_PersistentDocumentModel::BASE_MODEL)
				{
					return 'f_persistentdocument_PersistentDocument';
				}

				list ($package, $docName) = explode('/', $this->getDocumentType());
				list (, $packageName) = explode('_', $package);
				return $packageName . "_persistentdocument_" . $docName . ($this->getType() === 'DocumentArray' ?  '[]' : '');
			default:
				return 'string';
		}
	}
}