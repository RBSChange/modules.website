<?php
class PHPTAL_Php_Attribute_CHANGE_Menuitem extends PHPTAL_Php_Attribute
{
	private $separator;
	
    /**
     * Called before element printing.
     * Default implementation is for backwards compatibility only. Please always override both before() and after().
     */
    public function before(PHPTAL_Php_CodeWriter $codewriter)
    {
    	$this->separator = 'null';	
    	foreach ($codewriter->splitExpression($this->expression) as $expr) 
    	{
    		list($attribute, $value) = $this->parseSetExpression($expr);
    		if ($attribute == "separator")
			{
				$this->separator = $this->evaluate($value);
			}
    	}
    	$codewriter->pushCode('ob_start()');
    }

    public function after(PHPTAL_Php_CodeWriter $codewriter)
    {
    	$attributes = array();
    	foreach ($this->phpelement->getAttributeNodes() as $attrNode) 
    	{
    		if ($attrNode instanceof PHPTAL_Dom_Attr) 
    		{
    			if ($attrNode->getNamespaceURI() == '')
    			{
    				$attributes[$attrNode->getLocalName()] = $attrNode->getValue();
    			}
    		}
    	} 
       $codewriter->pushCode('echo PHPTAL_Php_Attribute_CHANGE_Menuitem::render($ctx->item, '.var_export($attributes, true).', $ctx->repeat->item, trim(ob_get_clean()), '.$this->separator.')');
    }
   
    public static function render($menuItem, $attributes, $controller, $content, $separator = null)
    {
    	foreach ($attributes as $name => $value)
    	{
    		$attributes[$name] = str_replace(array('%id', '%level', '%index'), array($controller->itemId, $controller->currentLevel, $controller->index), $value);
    	}
    	if ($content)
    	{
    		$menuItem->setLabel($content);
    	}
    	if ($controller->isCurrent)
    	{
    		$html = "<strong class=\"current\">".$menuItem->getLabel()."</strong>";
    	}
    	else if ($menuItem->hasUrl())
    	{
    		$onClick = $menuItem->getOnClick();
	    	if ($onClick)
	    	{
				$attributes['onclick'] = $onClick;
	    	}
	    	if ($menuItem->getPopup())
	    	{
	    		$param = $menuItem->getPopupParameters();
	    		$html = LinkHelper::getPopupLink($menuItem, null, 'link', '', $attributes, $param['width'], $param['height']);
	    	}
	    	else
	    	{
	    		$html = LinkHelper::getLink($menuItem, null, 'link', '', $attributes);
	    	}
    	}
    	else 
    	{
    		$html = $menuItem->getLabel();
    	}
    	
    	if ($separator != "" && !$controller->end)
    	{
    		$html .= "<span>".$separator."</span>";
    	}
    	
    	return $html;
    }
}
