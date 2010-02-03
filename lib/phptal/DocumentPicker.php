<?php
class PHPTAL_Php_Attribute_CHANGE_documentpicker extends FormElement
{
	/**
	 * @see ChangeTalAttribute::getEvaluatedParameters()
	 *
	 * @return String[]
	 */
	protected function getEvaluatedParameters()
	{
		return array('value', 'evaluatedname', 'evaluatedlabel');
	}
}