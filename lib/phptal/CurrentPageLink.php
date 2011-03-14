<?php
/**
 * @package phptal.php.attribute
 * @example <a change:currentpagelink="extraParamArray">...</a>
 * @example <form change:currentpagelink="extraParamArray">...</form>
 * @example <a change:currentpagelink="extraParamName 'extraParamValue'">...</a>
 * @example <a change:currentpagelink="" extraParamName="extraParamValue">...</a>
 */
class PHPTAL_Php_Attribute_CHANGE_currentpagelink extends ChangeTalAttribute
{
	/**
	 * @var string
	 */
	private $attrName = 'href';
	
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
	
	/**
	 * @param array $params
	 * @return string
	 */
	public static function renderCurrentpagelink($params)
	{
		$extraParams = (isset($params['extraparams']) && is_array($params['extraparams'])) ? $params['anchor'] : array();
		if (isset($params['module']))
		{
			$extraParams = array($params['module'] . 'Param' => $extraParams);
		}
		
		$ignoredParams = array('tagname', 'extraparams', 'anchor', 'module', 'class', 'title', 'rel');
		foreach ($params as $name => $value)
		{
			if (in_array($name, $ignoredParams))
			{
				continue;
			}
			$extraParams[$name] = $value;
		}
		return LinkHelper::getCurrentUrl($extraParams) . (isset($params['anchor']) ? ('#' . $params['anchor']) : '');
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
		else if ($tagName == 'input')
		{
			$this->attrName = 'value';
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
		$preservedAttributes = array('class', 'title', 'rel', 'name',  'type');
		foreach (array_keys($this->tag->attributes) as $name)
		{
			if (in_array($name, $preservedAttributes))
			{
				continue;
			}
			unset($this->tag->attributes[$name]);
		}
		$this->tag->attributes[$this->attrName] = ('<?php echo PHPTAL_Php_Attribute_CHANGE_currentpagelink::renderCurrentpagelink(' . $parametersString . '); ?>');
	}
}
