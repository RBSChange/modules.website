<?php
class website_tree_Node implements website_tree_INode
{
	private $attributes = array();
	
	private $id;
	
	private $hasChildren = false;
	
	/**
	 * @return array<string, string>
	 */
	public function getAttibutes()
	{
		return $this->attributes;
	}
	
	/**
	 * @return string
	 *
	 */
	public function getId()
	{
		return $this->id;
	}
	
	/**
	 * @return boolean
	 */
	public function hasChildren()
	{
		return $this->hasChildren;
	}
	
	public function __construct($id, $attributes = array(), $hasChildren = false)
	{
		$this->id = $id;
		$this->attributes = $attributes;
		$this->hasChildren = $hasChildren;
	}
	
	function __call($name, $arguments)
	{
		$matches = array();
		if (preg_match('/^(has|get|set)(\w*)Attribute$/', $name, $matches))
		{
			$attributeName = strtolower($matches[2]);
			switch ($matches[1])
			{
				case 'has' :
					return array_key_exists($attributeName, $this->attributes);
				case 'get' :
					if (isset($this->attributes[$attributeName]))
					{
						return $this->attributes[$attributeName];
					}
					return null;
				case 'get' :
					$this->attributes[$attributeName] = $arguments[0];
					break;
			}
		}
		return null;
	}
}
