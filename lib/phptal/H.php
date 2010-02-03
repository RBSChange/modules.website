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
class PHPTAL_Php_Attribute_CHANGE_h extends ChangeTalAttribute
{
	
	/**
	 * @see ChangeTalAttribute::end()
	 *
	 */
	public function end()
	{
		$parameters = array();
		$parameters[] =	'"tagname" =>' . var_export($this->tag->name, true);
		if ($this->hasParameter('level'))
		{
			$parameters[] = '"level" =>' . $this->getParameter('level');
		}
		$parametersString = 'array(' . implode(', ', $parameters) . ')';
		$this->tag->generator->doEchoRaw($this->getRenderClassName() . '::renderEndTag(' . $parametersString  . ')');
	}
	
	/**
	 * @see ChangeTalAttribute::start()
	 *
	 */
	public function start()
	{
		$this->tag->headFootDisabled = true;
		parent::start();
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
		if (preg_match("/^h(\d)$/i", $tagName, $matches))
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
			switch ($level) 
			{
				case 1:
					$class = 'heading-one';
					break;
				case 2:
					$class = 'heading-two';
					break;	
				case 3:
					$class = 'heading-three';
					break;
				case 4:
					$class = 'heading-four';
					break;
				case 5:
					$class = 'heading-five';
					break;
				case 6:
					$class = 'heading-six';
					break;				
				default:
					$class = '';
					break;
			}
		}
		
		if (!empty($class))
		{
			$result .= ' class="'. $class . '"'; 
		}		
		return $result . '>';
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