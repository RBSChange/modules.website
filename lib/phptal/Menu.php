<?php
class PHPTAL_Php_Attribute_CHANGE_Menu extends PHPTAL_Php_Attribute
{
	
    private $var;
    
    public function before(PHPTAL_Php_CodeWriter $codewriter)
    {
        $this->var = $codewriter->createTempVariable();
        
        // alias to repeats handler to avoid calling extra getters on each variable access
        $codewriter->doSetVar($this->var, '$ctx->repeat');

        list ($code, $datas) = $this->initMenu($codewriter);
          
        // instantiate controller using expression
        $codewriter->doSetVar( $this->var.'->item', 'new PHPTAL_MenuRepeatController('.$code.')'."\n" );
        
        foreach ($datas as $propertyName => $value) 
        {
        	$codewriter->doSetVar( $this->var.'->item->' . $propertyName, var_export($value, true));
        }
        
       	$websitePage = $codewriter->evaluateExpression('website_page');
       	$codewriter->doSetVar( $this->var.'->item->websitePage', $websitePage);
        
        
		$codewriter->doEchoRaw('PHPTAL_Php_Attribute_CHANGE_Menu::startMenu('.$this->var.'->item)');
		        
        $codewriter->pushContext();
        
        // Lets loop the iterator with a foreach construct
        $codewriter->doForeach('$ctx->item', $this->var.'->item');
        $codewriter->doEchoRaw($this->var.'->item->startLi()');
        
    }

    public function after(PHPTAL_Php_CodeWriter $codewriter)
    {
    	$codewriter->doEchoRaw($this->var.'->item->endLi()');
        $codewriter->doEnd('foreach');
        $codewriter->popContext();
        $codewriter->doEchoRaw('PHPTAL_Php_Attribute_CHANGE_Menu::endMenu('.$this->var.'->item)');
        $codewriter->recycleTempVariable($this->var);
    }
    
    /**
     * @param PHPTAL_MenuRepeatController $menuRepeater
     */
    public static function startMenu($menuRepeater)
    {
        return ($menuRepeater->skipMasterUL == true) ? '' : self::getUL($menuRepeater, 0, 0);
    }
    
    /**
     * @param PHPTAL_MenuRepeatController $menuRepeater
     */
    public static function endMenu($menuRepeater)
    {
        return ($menuRepeater->skipMasterUL == true) ? '' : '</' .$menuRepeater->listTag. '>';
    }
    
    /**
     * @param PHPTAL_Php_CodeWriter $codewriter
     */
    protected function initMenu($codewriter)
    {
        $expressions = $codewriter->splitExpression($this->expression);
        $datas = array();
		$code = null;
		$maxLevel = '20';
		$onlyTopics = 'false';
		
        // foreach attribute
        foreach ($expressions as $exp)
        {
            list($attribute, $value) = $this->parseSetExpression($exp);
            switch (strtolower($attribute))
            {
            	case 'menuobject':
            		$code = $codewriter->evaluateExpression($value);
            		break;
            	case 'maxlevel':
            		$maxLevel = $codewriter->evaluateExpression($value);
            		break;
            	case 'onlytopics':
            		$onlyTopics = f_util_Convert::toBoolean($value);
            		break;
            		
            	case 'class':
            		$datas['masterUlClass'] = $codewriter->evaluateExpression($value);
            		break;
            	case 'id':
            		$datas['masterUlId'] = $codewriter->evaluateExpression($value);
            		break;
            		
            	case 'liclass':
            		$datas['liClass'] = $value;
            		break;
            	case 'lioddclass':
            		$datas['liOddClass'] = $value;
            		break;
            	case 'lievenclass':
            		$datas['liEvenClass'] = $value;
            		break;
            	case 'lifirstclass':
            		$datas['liFirstClass'] = $value;
            		break;
            	case 'lilastclass':
            		$datas['liLastClass'] = $value;
            		break;
            	case 'ulclass':
            		$datas['ulClass'] = $value;
            		break;
            	case 'licurrentclass':
            		$datas['liCurrentClass'] = $value;
            		break;
            	case 'liinpathclass':
            		$datas['liInPathClass'] = $value;
            		break;
            	case 'masterulclass':
            		$datas['masterUlClass'] = $value;
            		break;
            	case 'masterulid':
            		$datas['masterUlId'] = $value;
            		break;
            	case 'columnbreak':
            	    $datas['columnBreak'] = explode('/', $value);
            		break;
            	case 'linebreak':
            		 $datas['lineBreak'] = f_util_Convert::toInteger($value);
            		break;
                case 'anchors':
            		$anchors = f_util_Convert::toInteger($value);
            		break;
            	case 'lihaschildrenclass':
            		$datas['liHasChildrenClass'] = $value;
            		break;
            	case 'lifirstinlevelclass':
            		$datas['liFirstInLevelClass'] =  $value;
            		break;
            	case 'lilastinlevelclass':
            		$datas['liLastInLevelClass'] = $value;
            		break;
            	case 'skipmasterul':
            		$datas['skipMasterUL'] = f_util_Convert::toBoolean($value);
            		break;
            	case 'listtag':
            		$datas['listTag'] = strcasecmp($value, 'ol') == 0 ? 'ol' : 'ul';
            		break;
            	default :
            		$matches = array();
            		if (preg_match('/^liclass\[([\d+])\]$/', $attribute, $matches))
            		{
            			$datas['liClassByLevel'][intval($matches[1])] = $value;
            		}
            		else if (preg_match('/^ulclass\[([\d+])\]$/', $attribute, $matches))
            		{
            			$datas['ulClassByLevel'][intval($matches[1])] = $value;
            		}
            }
        }
        if (empty($code))
        {
        	throw new PHPTAL_Exception("Missing \"menuObject\" parameter in \"change:menu\" tag.");
        }
        
        return array($code.', ' . $maxLevel.', ' . $onlyTopics, $datas);
    }



    /**
     * @param PHPTAL_MenuRepeatController $controller
     * @param integer $level
     * @param integer $id
     */
    public static function getUL($controller, $level, $id)
    {
    	$ul = '<' . $controller->listTag;
    	$class = array();
    	if ($controller->currentLevel == -1)
    	{
	    	if ($controller->masterUlClass)
	    	{
	    		$class[] = $controller->masterUlClass;
	    	}
	    	if ($controller->masterUlId)
	    	{
	    		$ul .= ' id="'.$controller->masterUlId.'"';
	    	}
    	}
    	if ($controller->ulClass)
    	{
    		$class[] = $controller->ulClass;
    	}
    	if (isset($controller->ulClassByLevel[$level]))
    	{
    		$class[] = $controller->ulClassByLevel[$level];
    	}

    	if (count($class))
    	{
			$class = array_unique($class);
			foreach ($class as $i => $c)
			{
				$class[$i] = str_replace(array('%id', '%level', '%position', '%index'), array($id, $level, isset($controller->indexArray[$level])? $controller->indexArray[$level] : '0', $controller->index), $c);
			}
    		$ul .= ' class="'.trim(join(' ', $class)).'"';
    	}

    	$ul .= '>';
    	return $ul;
    }
}

class  PHPTAL_MenuRepeatController extends PHPTAL_RepeatController
{
	//LI Managment
	
	public $liClass;	
	public $liCurrentClass;
	public $liInPathClass;
	public $liOddClass;
	public $liEvenClass;
	public $liFirstClass;
	public $liLastClass;
	public $liClassByLevel = array();
	public $liFirstInLevelClass;
	public $liLastInLevelClass;
	public $liHasChildrenClass;
	public $itemOddArray = array();
	
	
	//UL Managment
	public $listTag = 'ul';
	public $masterUlClass;
	public $masterUlId;
	public $ulClass;
	public $ulClassByLevel = array();
	
	
	//Navigation managment
	public $isCurrent = false;
	
	public $isInPath = false;
	
	public $itemIndex = 0;
	
	public $itemId = 0;
		
	public $indexArray = array();
	
	public $source = array();
	
	public $columnBreak = array();
	
	public $anchors = 0;
	
	public $skipMasterUL = false;	
	
	public $currentLevel = -1;	
	
	public $startLevel = -1;	
	
	public $maxLevel = 1;
	
	public $lineBreak = 0;
	
	public $currentPosition = 0;
		
	public $onlyTopics = false;
	
	public $websitePage;
		
	 /**
     * Construct a new RepeatController.
     *
     * @param $source array, string, iterator, iterable.
     */
    public function __construct($source, $maxLevel, $onlyTopics)
    {
    	$maxLevel = intval($maxLevel);
    	if ($maxLevel < 1 || $maxLevel > 20) {$maxLevel = 20;}
    	
    	$array = array();
    	$baseLevel = -1;
    	foreach ($source as $menuItem) 
    	{
    		if ($baseLevel === -1) { $baseLevel = $menuItem->getLevel();}
    		if (($menuItem->getLevel() - $baseLevel < $maxLevel) && (!$onlyTopics || $menuItem->isTopic()))
    		{
    			$array[] = $menuItem;
    		}
    		
    	}
		parent::__construct($array);
    }
    
	/**
	 * @return website_MenuItem
	 */
	protected function getCurrentMenuItem()
	{
		return $this->current();
	}
	
	/**
	 * @return integer
	 */
    protected function getNextItemLevel()
    {
    	return $this->iterator->valid() ? $this->iterator->current()->getLevel() : $this->startLevel;
    }
	
	public function startLi()
    {
    	$rawHtml = array();
    	$menuItem = $this->getCurrentMenuItem();
    	$menuItemLevel = $menuItem->getLevel();
    	if ($this->currentLevel === -1) 
    	{
    		$this->startLevel = $menuItemLevel;
    		$this->currentLevel = $menuItemLevel;
    	}
		if (!isset($this->indexArray[$menuItemLevel])) 
		{
			$this->indexArray[$menuItemLevel] = 0;
		}
		
		if ($menuItemLevel == $this->currentLevel)
		{
			if (($this->lineBreak > 0) && ($this->itemIndex > 0) && ($this->itemIndex % $this->lineBreak == 0))
			{
				$rawHtml[]  = '</'.$this->listTag.'>';
				$rawHtml[] = PHPTAL_Php_Attribute_CHANGE_Menu::getUL($this, $menuItemLevel, 0);
				
				$this->indexArray[$menuItemLevel] = 0;
				$this->itemOddArray[$menuItemLevel] = false;	
				$this->itemIndex = 0;
				$this->index = 0;
			}
			else
			{
				$this->itemIndex++;
				$this->indexArray[$menuItemLevel]++;
				$this->itemOddArray[$menuItemLevel] =  !(isset($this->itemOddArray[$menuItemLevel]) && $this->itemOddArray[$menuItemLevel]);
			}	
		}
		else if ($menuItemLevel > $this->currentLevel)
		{
			$this->itemOddArray[$menuItemLevel] = false;
			$this->indexArray[$menuItemLevel] = 0;
			$this->itemIndex = 0;
			$rawHtml[] = PHPTAL_Php_Attribute_CHANGE_Menu::getUL($this, $menuItemLevel, $menuItem->getId());
		}
		else if ($menuItemLevel < $this->currentLevel)
		{
			$this->itemOddArray[$menuItemLevel] = ! (isset($this->itemOddArray[$menuItemLevel]) && $this->itemOddArray[$menuItemLevel]);
			$this->indexArray[$menuItemLevel]++;
		}
		$this->itemId = $menuItem->getId();		
		if ($this->websitePage instanceof website_Page)
		{
			$this->isCurrent = $this->websitePage->getId() == $this->itemId;
			$this->isInPath = in_array($this->itemId, $this->websitePage->getAncestorIds());
		}
		$this->currentLevel = $menuItemLevel;
		$this->currentPosition = $this->indexArray[$menuItemLevel];
		$rawHtml[] = $this->getLI($menuItemLevel, $menuItem->getId());
		return implode('', $rawHtml);
    }
    
    public function endLi()
    {
    	$nextLevel = $this->getNextItemLevel();
    	if ($nextLevel == $this->currentLevel)
    	{
    		return '</li>';
    	}
    	else if ($nextLevel < $this->currentLevel)
    	{
    		return '</li>' . str_repeat('</'.$this->listTag.'></li>', $this->currentLevel - $nextLevel);
    	}
    }
        
    /**
     * @param integer $level
     * @param integer $id
     */
    protected function getLI($level, $id)
    {
    	$li = '<li';
    	$class = array();
    	
    	if ($this->liClass)
    	{
    		$class[] = $this->liClass;
    	}
		if ($this->liCurrentClass && $this->isCurrent)
    	{
    		$class[] = $this->liCurrentClass;
    	}
		if ($this->liInPathClass && ($this->isInPath || $this->isCurrent))
    	{
    		$class[] = $this->liInPathClass;
    	}
    	if ($this->liOddClass && $this->itemOddArray[$level])
    	{
    		$class[] = $this->liOddClass;
    	}
    	if ($this->liEvenClass && !$this->itemOddArray[$level])
    	{
    		$class[] = $this->liEvenClass;
    	}
    	if ($this->liFirstClass && $this->index == 0)
    	{
    		$class[] = $this->liFirstClass;
    	}
    	if ($this->liLastClass && $this->index == $this->length() - 1)
    	{
    		$class[] = $this->liLastClass;
    	}
    	if (isset($this->liClassByLevel[$level]))
    	{
    		$class[] = $this->liClassByLevel[$level];
    	}
    	if ($this->liFirstInLevelClass && isset($this->indexArray[$level]) && intval($this->indexArray[$level]) === 0)
    	{
    		$class[] = $this->liFirstInLevelClass;
    	}

    	// Check if element is the last one in the current level
    	if ($this->liLastInLevelClass || $this->liHasChildrenClass)
    	{
    		$nextElem = $this->iterator->valid() ? $this->iterator->current() : null;
			if ($nextElem === null ||  $nextElem->getLevel() < $this->getCurrentMenuItem()->getLevel())   
			{ 		
    		   $class[] = $this->liLastInLevelClass;
    		}
    		elseif ($nextElem !== null ||  $nextElem->getLevel() > $this->getCurrentMenuItem()->getLevel())   
    		{
    			$class[] = $this->liHasChildrenClass;
    		}
    	}

    	if ($this->columnBreak && ($this->columnBreak[0] == ($level + 1))
    		&& ((($this->indexArray[$level] + 1) % $this->columnBreak[1]) == 0))
    	{
    		$class[] = 'column-break-before';
    	}
    	else if ($this->columnBreak && ($this->columnBreak[0] == ($level + 1))
    	&& ($this->indexArray[$level] > 0)
    	&& ((($this->indexArray[$level] + 1) % $this->columnBreak[1]) == 1))
    	{
    		$class[] = 'column-break-after';
    	}

    	if (count($class))
    	{
			$class = array_unique($class);
    		foreach ($class as $i => $c)
			{
				$class[$i] = str_replace(array('%id', '%level', '%position', '%index'), array($id, $level, isset($this->indexArray[$level])? $this->indexArray[$level] : '0', $this->index), $c);
			}
    		$li .= ' class="'.trim(join(' ', $class)).'"';
    	}
    	$li .= '>';

    	if (($this->anchors > 0) && ($level < $this->anchors))
    	{
    	    $li .= sprintf('<a name="menu_%d" id="menu_%d"></a>', $id, $id);
    	}
    	return $li;
    }
}