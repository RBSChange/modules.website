<?php
/**
 * change:popup
 *  <a href="http://www.rbschange.fr" change:popup="width:200,height:100" >
 * @package phptal.php.attribute
 */
class PHPTAL_Php_Attribute_CHANGE_Popup extends PHPTAL_Php_Attribute
{
	private $pageId;
	private $lang;
	private $documentId;

		/**
     * Called before element printing.
     */
    public function before(PHPTAL_Php_CodeWriter $codewriter)
    {
		$popupParameters = self::parsePopupArg($this->expression);
		if (count($popupParameters))
		{
			$attr = $this->phpelement->getOrCreateAttributeNode('onclick');
			$attr->setValueEscaped(self::getOnClick($popupParameters));
		}
	}

	/**
     * Called after element printing.
     */
    public function after(PHPTAL_Php_CodeWriter $codewriter)
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