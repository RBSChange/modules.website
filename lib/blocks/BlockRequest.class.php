<?php
class block_BlockRequest
{


    /**
     * Block handler.
     *
     * @var block_BlockHandler
     */
    private $handler = null;


    /**
     * Block request parameters.
     *
     * @var array
     */
	protected $parameters = array();


	protected $files = array();


	private function __construct()
	{

	}


	/**
	 * Retrieve a new instance of block_BlockRequest
	 *
	 * @return block_BlockRequest
	 */
	public static function getNewInstance()
	{
		$className = __CLASS__;
		return new $className();
	}


	/**
	 * Initialize the block request by retrieving the related parameters
	 * from the global request.
	 *
	 * @param block_BlockHandler $handler
	 * @return block_BlockRequest
	 */
	public function initialize ($handler)
	{
		$this->handler = $handler;

        $this->setParameters(
            $this->handler->getGlobalRequest()->getModuleParameters(
                $this->handler->getModuleName()
            )
        );

        $files = $this->handler->getGlobalRequest()->getFiles();

        if ($files && is_array($files) && isset($files[$this->handler->getModuleName().'Param']))
        {
            $this->files = $files[$this->handler->getModuleName().'Param'];
        }

		return $this;
	}


	/**
	 * Clear all parameters associated with this request.
	 *
	 * @return block_BlockRequest
	 */
	public function clearParameters ()
	{
		$this->parameters = null;
		$this->parameters = array();

		return $this;
	}


	/**
	 * Retrieve a parameter.
	 *
	 * @param string A parameter name.
	 * @param mixed  A default parameter value.
	 *
	 * @return mixed A parameter value, if the parameter exists, otherwise
	 */
	public function & getParameter ($name, $default = null)
	{
		$retval =& $default;

		if (isset($this->parameters[$name]))
		{
			$retval =& $this->parameters[$name];
		}

		return $retval;
	}


	/**
	 * Retrieve an array of parameter names.
	 *
	 * @return array An indexed array of parameter names.
	 */
	public function getParameterNames ()
	{
		return array_keys($this->parameters);
	}


	/**
	 * Retrieve an array of parameters.
	 *
	 * @return array An associative array of parameters.
	 */
	public function getParameters ()
	{
		return $this->parameters;
	}


	/**
	 * Retrieve an array of uploaded files.
	 *
	 * @return array An associative array of upload files.
	 */
	public function getFiles ()
	{
		return $this->files;
	}


	/**
	 * Retrieve an array of uploaded file's information.
	 *
	 * @param string $name Name of the field.
	 * @return array An associative array of uploaded file's inforamtion.
	 */
	public function getUploadedFileInformation ($name)
	{
		$info = array();
		foreach ($this->files as $k => &$array)
		{
			$info[$k] = $array[$name];
		}
		return $info;
	}


	/**
	 * Retrieve an array of parameters for use in the global request.
	 *
	 * @return array An associative array of parameters.
	 */
	public function getParametersForGlobalRequest ($parameters = null)
	{
	    if (is_null($parameters))
	    {
	        $parameters = $this->parameters;
	    }
		return array($this->handler->getModuleName() . 'Param' => $parameters);
	}


	/**
	 * Indicates whether or not a parameter exists.
	 *
	 * @param string A parameter name.
	 *
	 * @return bool true, if the parameter exists, otherwise false.
	 */
	public function hasParameter ($name)
	{
		return isset($this->parameters[$name]);
	}
	
	/**
	 * Indicates whether or not a parameter exists and the value is non empty
	 * @param String $name
	 * @see hasParameter
	 * @return Boolean
	 */
	public function hasNonEmptyParameter($name)
	{
		if (!isset($this->parameters[$name])) return false;
		if (is_array($this->parameters[$name])) return f_util_ArrayUtils::isNotEmpty($this->parameters[$name]);
		return f_util_StringUtils::isNotEmpty($this->parameters[$name]);
	}

	/**
	 * Remove a parameter.
	 *
	 * @param string A parameter name.
	 *
	 * @return string A parameter value, if the parameter was removed,
	 *                otherwise null.
	 */
	public function & removeParameter ($name)
	{
		$retval = null;

		if (isset($this->parameters[$name]))
		{
			$retval =& $this->parameters[$name];
			unset($this->parameters[$name]);
		}

		return $retval;
	}


	/**
	 * Set a parameter.
	 *
	 * If a parameter with the name already exists the value will be overridden.
	 *
	 * @param string A parameter name.
	 * @param mixed  A parameter value.
	 *
	 * @return block_BlockRequest
	 */
	public function setParameter ($name, $value)
	{
		$this->parameters[$name] = $value;

		return $this;
	}


	/**
	 * Set a parameter by reference.
	 *
	 * If a parameter with the name already exists the value will be
	 * overridden.
	 *
	 * @param string A parameter name.
	 * @param mixed  A reference to a parameter value.
	 *
	 * @return block_BlockRequest
	 */
	public function setParameterByRef ($name, &$value)
	{
		$this->parameters[$name] =& $value;

		return $this;
	}


	/**
	 * Set an array of parameters.
	 *
	 * If an existing parameter name matches any of the keys in the supplied
	 * array, the associated value will be overridden.
	 *
	 * @param array An associative array of parameters and their associated
	 *              values.
	 *
	 * @return block_BlockRequest
	 */
	public function setParameters ($parameters)
	{
	    if ($parameters && is_array($parameters))
	    {
		    $this->parameters = array_merge($this->parameters, $parameters);
	    }

		return $this;
	}


	/**
	 * Set an array of parameters by reference.
	 *
	 * If an existing parameter name matches any of the keys in the supplied
	 * array, the associated value will be overridden.
	 *
	 * @param array An associative array of parameters and references to their
	 *              associated values.
	 *
	 * @return block_BlockRequest
	 */
	public function setParametersByRef (&$parameters)
	{
		foreach ($parameters as $key => &$value)
		{
			$this->parameters[$key] =& $value;
		}

		return $this;
	}
}