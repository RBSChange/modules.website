<?php
class PHPTAL_Php_Attribute_CHANGE_Documentpicker extends FormElement
{
	/**
	 * @see ChangeTalAttribute::getEvaluatedParameters()
	 *
	 * @return string[]
	 */
	protected function getEvaluatedParameters()
	{
		return array('value', 'evaluatedname', 'evaluatedlabel');
	}
}