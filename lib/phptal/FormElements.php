<?php
class FormElement extends ChangeTalAttribute 
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
	
	/**
	 * @see ChangeTalAttribute::getRenderClassName()
	 *
	 * @return String
	 */
	protected function getRenderClassName()
	{
		return 'website_FormHelper';
	}
}

/**
 * @example <anytag change:submit="name toto"/>
 */
class PHPTAL_Php_Attribute_CHANGE_Submit extends FormElement
{

}

/**
 * @example <anytag change:textinput="name toto; label &modules.tutu.tata.titiLabel"/>
 */
class PHPTAL_Php_Attribute_CHANGE_Textinput extends FormElement
{

}
class PHPTAL_Php_Attribute_CHANGE_Radioinput extends FormElement
{

}

class PHPTAL_Php_Attribute_CHANGE_Checkboxinput extends FormElement
{

}

class PHPTAL_Php_Attribute_CHANGE_Passwordinput extends FormElement
{
	
}

class PHPTAL_Php_Attribute_CHANGE_Booleaninput extends FormElement
{
	
}

class PHPTAL_Php_Attribute_CHANGE_Fileinput extends FormElement
{
	
}

class PHPTAL_Php_Attribute_CHANGE_Uploadfield extends FormElement
{
	
}

class PHPTAL_Php_Attribute_CHANGE_Listmultifield extends FormElement 
{
	
}

/**
 * @example <anytag change:dateinput="name toto; label &modules.tutu.tata.titiLabel; format dd/yy/uu"/>
 */
class PHPTAL_Php_Attribute_CHANGE_Dateinput extends FormElement
{
	/**
	 * @see FormElement::getEvaluatedParameters()
	 *
	 * @return array
	 */
	protected function getEvaluatedParameters()
	{
		$evaluatedParameters = parent::getEvaluatedParameters();
		$evaluatedParameters[] = 'startdate';
		$evaluatedParameters[] = 'enddate';
		return $evaluatedParameters;
	}
}

/**
 * @example <anytag change:errors="[key myKey]"/>
 */
class PHPTAL_Php_Attribute_CHANGE_Errors extends FormElement
{

}

/**
 * @example <anytag change:messages="[key myKey]"/>
 */
class PHPTAL_Php_Attribute_CHANGE_Messages extends FormElement
{

}

/**
 * @example <anytag change:hiddeninput="name toto;"/>
 */
class PHPTAL_Php_Attribute_CHANGE_Hiddeninput extends FormElement
{

}

class PHPTAL_Php_Attribute_CHANGE_Richtextinput extends FormElement 
{
	
}

class PHPTAL_Php_Attribute_CHANGE_Bbcodeinput extends FormElement 
{
	
}

class PHPTAL_Php_Attribute_CHANGE_Durationinput extends FormElement 
{
	
}

class PHPTAL_Php_Attribute_CHANGE_Selectinput extends FormElement 
{
	/**
	 * @see FormElement::getEvaluatedParameters()
	 *
	 * @return array
	 */
	protected function getEvaluatedParameters()
	{
		$evaluatedParameters = parent::getEvaluatedParameters();
		$evaluatedParameters[] = 'list';
		return $evaluatedParameters;
	}
}

/**
 * @example <anytag change:field="name toto;"/>
 */
class PHPTAL_Php_Attribute_CHANGE_Field extends FormElement
{
	
	/**
	 * @see FormElement::getEvaluatedParameters()
	 *
	 * @return array
	 */
	protected function getEvaluatedParameters()
	{
		$evaluatedParameters = parent::getEvaluatedParameters();
		$evaluatedParameters[] = 'startdate';
		$evaluatedParameters[] = 'enddate';
		return $evaluatedParameters;
	}

}

/**
 * @example <anytag change:textarea="name toto;"/>
 */
class PHPTAL_Php_Attribute_CHANGE_Textarea extends FormElement
{

}

class PHPTAL_Php_Attribute_CHANGE_Fieldlabel extends FormElement
{

}

class PHPTAL_Php_Attribute_CHANGE_Label extends FormElement
{
	/**
     * Called before element printing.
     */
    public function before(PHPTAL_Php_CodeWriter $codewriter)
    {
		// We rewrite element
		$this->phpelement->headFootDisabled = true;	
		parent::before($codewriter);
	}
	
	/**
     * Called after element printing.
     */
    public function after(PHPTAL_Php_CodeWriter $codewriter)
    {
		$codewriter->doEchoRaw('website_FormHelper::endLabel()');
	}
}

/**
 * @example <anytag change:form="method get">[...]</anytag>
 */
class PHPTAL_Php_Attribute_CHANGE_Form extends FormElement
{
		
	/**
	 * @see ChangeTalAttribute::getDefaultValues()
	 *
	 * @return String[]
	 */
	protected function getDefaultValues()
	{
		return array('showErrors' => false);
	}
	
	/**
     * Called before element printing.
     */
    public function before(PHPTAL_Php_CodeWriter $codewriter)
    {	
		$this->phpelement->headFootDisabled = true;	
		parent::before($codewriter);
	}
	
	/**
	 * @see ChangeTalAttribute::getRenderMethodName()
	 *
	 * @return String
	 */
	protected function getRenderMethodName()
	{
		return 'initialize';
	}

	/**
     * Called after element printing.
     */
    public function after(PHPTAL_Php_CodeWriter $codewriter)
    {
		$codewriter->doEchoRaw('website_FormHelper::finalize()');
	}
}
