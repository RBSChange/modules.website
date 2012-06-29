<?php
class PHPTAL_Php_Attribute_CHANGE_Select extends PHPTAL_Php_Attribute
{
	
	/**
	 * Called before element printing.
	 */
	public function before(PHPTAL_Php_CodeWriter $codewriter)
	{
		$this->expression = $this->extractEchoType($this->expression);
		$expressions = $codewriter->splitExpression($this->expression);
		
		$min	= 'null';
		$max	= 'null';
		$listId = 'null';
		$name   = 'null';
		$defaultValue = 'null';
		$id = 'null';
		$firstLabel = 'null';
		$firstValue = 'null';
		$class = 'null';
		
		foreach ($expressions as $exp)
		{
			list($attribute, $value) = $this->parseSetExpression($exp);
			switch ($attribute)
			{
				case 'min' :
					$min = is_numeric($value) ? $value : $codewriter->evaluateExpression($value);
					break;
				case 'max' :
					$max = is_numeric($value) ? $value : $codewriter->evaluateExpression($value);
					break;
				case 'listId' :
					$listId = $codewriter->evaluateExpression($value);
					break;
				case 'name' :
					$name = $codewriter->evaluateExpression($value);
					break;
				case 'defaultValue' :
					$defaultValue = is_numeric($value) ? $value : $codewriter->evaluateExpression($value);
					break;
				case 'id' :
					$id = $codewriter->evaluateExpression($value);
					break;
				case 'firstLabel' :
					if (f_util_StringUtils::beginsWith($value, "&"))
					{
						$firstLabel = "'". $value.";'";
					}
					else
					{
						$firstLabel = $codewriter->evaluateExpression($value);
					}
					break;
				case 'firstValue' :
					if(f_util_StringUtils::beginsWith($value, "&"))
					{
						$firstValue = "'".$value.";'";
					}
					else
					{
						$firstValue = $codewriter->evaluateExpression($value);
					}
					break;
				case 'class' :
					$class = $codewriter->evaluateExpression($value);
					break;

			}
		}
		$code = $this->_getCode($name, $min, $max, $listId, $defaultValue, $id, $firstLabel, $firstValue, $class);
		$codewriter->doEchoRaw($code);
	}

	protected function _getCode($name, $min, $max, $listId, $defaultValue, $id, $firstLabel, $firstValue, $class)
	{
		$code = 'PHPTAL_Php_Attribute_CHANGE_Select::buildSelect(' . $name . ', ' . $min . ', ' . $max . ', ' . $listId . ', ' . $defaultValue . ', ' . $id . ', ' . $firstLabel . ', ' . $firstValue . ', ' . $class . ')';
		return $code;
	}

	public static function buildSelect($name, $min, $max, $listId, $defaultValue, $id, $firstLabel, $firstValue, $class)
	{
		$html = '<select name="'.$name.'"';
		if(!is_null($id))
		{
			$html .= ' id="'.$id.'"';
		}
		if(!is_null($class))
		{
			$html .= ' class="'.$class.'"';
		}
		$html .= '>';
		$ls = LocaleService::getInstance();
		$lang = RequestContext::getInstance()->getLang();
		
		if (!is_null($firstLabel))
		{
			$key =  $ls->trans($firstLabel, array('html'));
			$firstLabel = ($key !== $firstLabel) ? $key : $ls->transformHtml($firstLabel, $lang);

			$html .= '<option value="';
			if (!is_null($firstValue))
			{
				$key = $ls->trans($firstValue, array('html'));
				$firstValue = ($key !== $firstValue) ? $key : $ls->transformAttr($firstValue, $lang);
				$html .= $firstValue;
			}
			$html .= '">'. $firstLabel .'</option>';
		}
		
		if (!is_null($min) && !is_null($max))
		{
			for ($i= min($min, $max) ; $i<= max($min, $max) ; $i++)
			{
				$html .= '<option value="'.$i.'"';
				if ($i == $defaultValue)
				{
					$html .= ' selected="selected"';
				}
				$html .= ">".$i."</option>";
			}
		}
		else if (!is_null($listId))
		{
			$list = list_ListService::getInstance()->getDocumentInstanceByListId($listId);
			foreach ($list->getItems() as $item)
			{
				$value = $item->getValue();
				$html .= '<option value="'.$ls->transformAttr($value, $lang).'"';
				if ($value == $defaultValue)
				{
					$html .= ' selected="selected"';
				}
				$html .= ">". $ls->transformHtml($item->getLabel(), $lang) ."</option>";
			}
		}

		$html .= "</select>";
		return $html;
	}

	/**
	 * Called after element printing.
	 */
	public function after(PHPTAL_Php_CodeWriter $codewriter)
	{
	}
}