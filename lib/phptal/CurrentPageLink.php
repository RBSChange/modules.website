<?php
/**
 * Examples:
 *	<a change:currentpagelink="extraParamArray">...</a>
 *	<form change:currentpagelink="extraParamArray">...</form>
 *	<a change:currentpagelink="extraParamName 'extraParamValue'">...</a>
 *	<a change:currentpagelink="" extraParamName="extraParamValue">...</a>
 * @package phptal.php.attribute
 */
class PHPTAL_Php_Attribute_CHANGE_Currentpagelink extends ChangeTalAttribute
{
	/**
	 * @var string
	 */
	private $attrName = 'href';
	
	/**
	 * @return string
	 */
	protected function getDefaultParameterName()
	{
		return 'extraparams';
	}
	
	/**
	 * @return boolean
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
		$extraParams = (isset($params['extraparams']) && is_array($params['extraparams'])) ? $params['extraparams'] : array();
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
	
	 /**
	 * Called before element printing.
	 */
	public function before(PHPTAL_Php_CodeWriter $codewriter)
	{
		$tagName = strtolower($this->phpelement->getLocalName());
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
		if ($this->phpelement->hasAttribute('class'))
		{
			$classes = explode(' ', $this->phpelement->getAttributeNS('', 'class'));
		}
		else
		{
			$classes = array();
		}
		if (!in_array('link', $classes))
		{
			$classes[] = 'link';
		}
		$this->phpelement->setAttributeNS('', 'class', implode(' ', $classes));
		
		$preservedAttributes = array('class', 'title', 'rel', 'name',  'type');
		$parametersString = $this->initParams($codewriter, $preservedAttributes);
		foreach ($this->phpelement->getAttributeNodes() as $attr)
		{
			/* @var $attr PHPTAL_Dom_Attr */
			if ($attr->getNamespaceURI() !== '' || in_array($attr->getLocalName(), $preservedAttributes))
			{
				continue;
			}
			$this->phpelement->removeAttributeNS($attr->getNamespaceURI(), $attr->getLocalName());
		}
		$this->phpelement->getOrCreateAttributeNode($this->attrName)
			->setValueEscaped('<?php echo PHPTAL_Php_Attribute_CHANGE_Currentpagelink::renderCurrentpagelink(' . $parametersString . '); ?>');
	}
}
