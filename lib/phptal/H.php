<?php
/**
 * If both title and localekey are defined, title has priority on localekey.
 * Parameters:
 * <ul>
 *   <li>level, default use current tag level, evaluated : the level of heading element to generate (i.e.: the X value in &lt;HX/&gt;)</li>
 *   <li>class, default auto, evaluated : the value of class attribute</li>
 * </ul>
 * @package website.lib.phptal
 * @example <hX change:h="level 3;class 'auto'">Title</hX>
 * @example => <h3 class="heading-three">label</h3>
 */
class PHPTAL_Php_Attribute_CHANGE_H extends ChangeTalAttribute
{
	/**
     * Called before element printing.
     */
    public function before(PHPTAL_Php_CodeWriter $codewriter)
    {
		$this->phpelement->headFootDisabled = true;
		parent::before($codewriter);
	}
	
	/**
     * Called after element printing.
     */
    public function after(PHPTAL_Php_CodeWriter $codewriter)
    {
		$parameters = array();
		$parameters[] =	'"tagname" =>' . var_export($this->phpelement->getLocalName(), true);
		if ($this->hasParameter('level'))
		{
			$parameters[] = '"level" =>' . $this->getParameter('level');
		}
		$parametersString = 'array(' . implode(', ', $parameters) . ')';
		$codewriter->doEchoRaw($this->getRenderClassName() . '::renderEndTag(' . $parametersString  . ')');
	}
	
	/**
	 * @see ChangeTalAttribute::getEvaluatedParameters()
	 *
	 * @return String[]
	 */
	protected function getEvaluatedParameters()
	{
		return array('level', 'class');
	}
	
	/**
	 * @see ChangeTalAttribute::getDefaultValues()
	 *
	 * @return String[]
	 */
	protected function getDefaultValues()
	{
		return array('class' => 'auto');
	}
	
	private static function getLevelFromTagName($tagName)
	{
		$matches = array();
		if (preg_match('/^h(\d)$/i', $tagName, $matches))
		{
			return $matches[1];
		}
		return 2;
	}
	
	/**
	 * @param array $params
	 * @return String
	 */
	public static function renderH($params)
	{
		if (isset($params['level']))
		{
			$level = intval($params['level']);			
		} 
		else 
		{
			$level = self::getLevelFromTagName($params['tagname']);
		}
		
		if ($level < 1) 
		{
			$level = 1;
		}
		elseif ($level > 6)
		{
			$level = 6;
		}
		
		$result = '<h' . $level;
		foreach (array('id', 'dir', 'title', 'xml:lang') as $attrName)
		{
			if (isset($params[$attrName]))
			{
				$result .= ' '.$attrName.'="'. $params[$attrName] . '"';
			}
		}
		
		$class = $params['class'];
		if ($class === 'auto')
		{
			$class = self::getClassByLevel($level);
		}
		
		if (!empty($class))
		{
			$result .= ' class="'. $class . '"'; 
		}
		return $result . '>';
	}
	
	private static $classByLevel = array(1 => "heading-one",
		2 => "heading-two", 3 => "heading-three",
		4 => "heading-four", 5 => "heading-five",
		6 => "heading-six");
	
	/**
	 * @param Integer $level
	 * @return String
	 */
	public static function getClassByLevel($level)
	{
		if (isset(self::$classByLevel[$level]))
		{
			return self::$classByLevel[$level];
		}
		return "";
	}
	
	public static function renderEndTag($params)
	{
		if (isset($params['level']))
		{
			$level = intval($params['level']);			
		} 
		else 
		{
			$level = self::getLevelFromTagName($params['tagname']);
		}
		return  '</h' . $level . '>';
	}
}