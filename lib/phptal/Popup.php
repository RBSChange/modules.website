<?php

//require_once 'PHPTAL/Php/Attribute.php';

// change:link
//   <a href="#"
//        change:link="page 14526; lang fr; anchor top"
//   >

/**
 * @package phptal.php.attribute
 */
class PHPTAL_Php_Attribute_CHANGE_popup extends PHPTAL_Php_Attribute
{
	private $pageId;
	private $lang;
	private $documentId;

	public function start()
	{
		$popupParameters = self::parsePopupArg($this->expression);

		if ( ! empty($popupParameters) )
		{
			$this->tag->attributes['onclick'] = self::getOnClick($popupParameters);
		}
	}

	public function end()
	{
	}

	public static function parsePopupArg($value)
	{
		$popupParameters = array();
		foreach (explode(',', $value) as $param)
		{
			list($pName, $pValue) = explode(':', $param);
			$popupParameters[strtolower(trim($pName))] = trim($pValue);
		}
		return $popupParameters;
	}

	/**
	 * @param array $popupParameters
	 * @return String
	 */
	public static function getOnClick($popupParameters)
	{
		$onClick = 'return accessiblePopup(this';
		if (isset($popupParameters['width']) && is_numeric($popupParameters['width']))
		{
			$onClick .= ', '.$popupParameters['width'];
		}
		if (isset($popupParameters['height']) && is_numeric($popupParameters['height']))
		{
			$onClick .= ', '.$popupParameters['height'];
		}
		$onClick .= ');';
		return $onClick;
	}
}