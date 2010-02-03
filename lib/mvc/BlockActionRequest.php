<?php
class website_BlockActionRequest implements f_mvc_Request 
{
	const MODEL_KEY = "website_BlockActionRequest";
	
	private static $emptyFiles = array ('name' => array (), 'type' => array (), 'tmp_name' => array (), 'error' => array (), 'size' => array ());
	
	/**
	 * @var array<String,array<String>>
	 */
	private $parameters;
	
	/**
	 * @var array<String,mixed>
	 */
	private $attributes = array();
	
	/**
	 * @var String
	 */
	private $moduleName;
	
	/**
	 * @var String
	 */
	private $actionName;
	
	private $errors = array();
	
	private $messages = array();
	
	private $files;
	
	
	/**
	 * @param array $parameters
	 * @param String $moduleName
	 * @param String $actionName
	 */
	public function __construct($parameters, $moduleName, $actionName)
	{
		$this->moduleName = $moduleName;
		$this->actionName = $actionName;
		$this->parameters = $parameters;
		if (!is_array($this->parameters))
		{
			$this->parameters = array();
		}
		$moduleParamName = $moduleName."Param"; 
		if (isset($_FILES[$moduleParamName]))
		{
			$this->files = $_FILES[$moduleParamName];
		}
		else
		{
			$this->files = self::$emptyFiles; 
		}
	}
	
	/**
	 * @return String
	 */
	public final function getModuleName()
	{
		return $this->moduleName;
	}
	
	/**
	 * @param String $paramName
	 * @return Boolean
	 */
	public function hasFile($paramName)
	{
		return isset($this->files["name"][$paramName]) && $this->files["error"][$paramName] == UPLOAD_ERR_OK;
	}
	
	/**
	 * We keep the objects that we created so two calls to getFile() returns the same object
	 * @var array<String, media_persistentdocument_tmpfile>
	 */
	private $fileInstances;
	
	/**
	 * @param String $paramName
	 * @return media_persistentdocument_file
	 */
	function getFile($paramName)
	{
		if (isset($this->fileInstances[$paramName]))
		{
			return $this->fileInstances[$paramName];
		}
		$fileName = $this->files['name'][$paramName];
		if ($this->files['error'][$paramName] != UPLOAD_ERR_OK)
		{
			throw new Exception("File ".$fileName." upload error");
		}
		
		$fileService = media_TmpfileService::getInstance();
		$file = $fileService->getNewDocumentInstance();
				
		$fileExtension = f_util_FileUtils::getFileExtension($fileName, true);
		$cleanFileName = basename($fileName, $fileExtension);
		
		$file->setLabel(f_util_StringUtils::utf8Encode($cleanFileName));
		$file->setNewFileName($this->files['tmp_name'][$paramName], f_util_StringUtils::utf8Encode($fileName));
		
		$this->fileInstances[$paramName] = $file;
		return $file;
	}
	
	/**
	 * @return media_persistentdocument_file[]
	 */
	function getFiles()
	{
		$files = array();
		foreach (array_keys($this->files['name']) as $name)
		{
			$files[] = $this->getFile($name);
		}
		return $files;
	}
	
	/**
	 * @return String
	 */
	public final function getActionName()
	{
		return $this->actionName;
	}
	
	/**
	 * @param String $name
	 * @param String $defaultValue
	 * @return String the value of the parameter or $defaultValue
	 */
	function getParameter($name, $defaultValue = null)
	{
		if (isset($this->parameters[$name]))
		{
			return $this->parameters[$name];
		}
		return $defaultValue;
	}
	
	/**
	 * @param String $name
	 * @return Boolean
	 */
	function hasNonEmptyParameter($name)
	{
		if (!$this->hasParameter($name))
		{
			return false;
		}
		
		$parameter = $this->getParameter($name);
		if (is_array($parameter))
		{
			return f_util_ArrayUtils::isNotEmpty($parameter);
		}
		return !f_util_StringUtils::isEmpty(strval($parameter));
	}
	

	/**
	 * @return array<String, array<String>>
	 */
	function getParameters()
	{
		return $this->parameters;
	}
	
	/**
	 * @param String $name
	 * @param mixed $value
	 */
	function setAttribute($name, $value)
	{
		$this->attributes[$name] = $value;
	}
	
	/**
	 * @param array<String, mixed> $attributes
	 */
	function setAttributes($attributes)
	{
		$this->attributes = $attributes;
	}
	
	/**
	 * @param String $name
	 * @param mixed $defaultValue
	 * @return mixed
	 */
	function getAttribute($name, $defaultValue = null)
	{
		if (isset($this->attributes[$name]))
		{
			return $this->attributes[$name];
		}
		return $defaultValue;
	}
	
	/**
	 * @return array<String, mixed>
	 */
	function getAttributes()
	{
		return $this->attributes;
	}
	
	/**
	 * @param String $name
	 * @return Boolean 
	 */
	function hasParameter($name)
	{
		return array_key_exists($name, $this->parameters);
	}
	
	/**
	 * @param String $name
	 * @return Boolean
	 */
	function hasAttribute($name)
	{
		return isset($this->attributes[$name]);
	}
	
	/**
	 * @return website_Page
	 */
	function getContext()
	{
		return website_BlockController::getInstance()->getContext();
	}
}