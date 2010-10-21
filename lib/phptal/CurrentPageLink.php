<?php
/**
 * @package phptal.php.attribute
 * @example <a change:currentpagelink="extraParamArray">...</a>
 * @example <form change:currentpagelink="extraParamArray">...</form>
 * @example <a change:currentpagelink="extraParamName 'extraParamValue'">...</a>
 */
class PHPTAL_Php_Attribute_CHANGE_currentpagelink extends ChangeTalAttribute
{
	
	private $attrName = "href";
	
	/**
	 * @return String
	 */
	protected function getDefaultParameterName()
	{
		return 'extraparams';
	}
	

	/**
	 * @return Boolean
	 */
	protected function evaluateAll()
	{
		return true;
	}
	
	public static function renderCurrentpagelink($params)
	{
		if (isset($params['extraparams']) && is_array($params['extraparams']))
		{
			$extraParams = $params['extraparams'];
		}
		else
		{
			$extraParams = array();
		}
		
		foreach ($params as $name => $value)
		{
			if ($name == "tagname" || $name == "class" || $name == "extraparams" || $name == "module" || $name == "title")
			{
				continue;
			}
			$extraParams[$name] = $value;
		}
		
		if (isset($params['module']))
		{
			$extraParams = array($params['module'] . 'Param' => $extraParams);
		}
		return LinkHelper::getCurrentUrl($extraParams);
	}
	
	public function start()
	{
		$tagName = strtolower($this->tag->name);
		if ($tagName == 'form')
		{
			$this->attrName = 'action';
		}
		else if ($tagName == 'iframe' || $tagName == 'img')
		{
			$this->attrName = 'src';
		}
		if (isset($this->tag->attributes['class']))
		{
			$classes = explode(' ', $this->tag->attributes['class']);
		}
		else
		{
			$classes = array();
		}
		if (!in_array('link', $classes))
		{
			$classes[] = 'link';
		}
		$this->tag->attributes['class'] = implode(' ', $classes);
		$parametersString = $this->initParams();
		foreach (array_keys($this->tag->attributes) as $name)
		{
			if ($name == "class" || $name == "title")
			{
				continue;
			}
			unset($this->tag->attributes[$name]);
		}
		$this->tag->attributes[$this->attrName] = ('<?php echo PHPTAL_Php_Attribute_CHANGE_currentpagelink::renderCurrentpagelink(' . $parametersString . '); ?>');
	}
}