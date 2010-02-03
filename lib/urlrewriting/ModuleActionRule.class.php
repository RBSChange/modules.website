<?php
class website_lib_urlrewriting_ModuleActionRule
	extends website_lib_urlrewriting_Rule
{

	/**
	 * The module the rule will redirect to.
	 *
	 * @var string
	 */
	private $module = null;


	/**
	 * The action the rule will redirect to.
	 *
	 * @var string
	 */
	private $action = null;


	/**
	 * Builds the rule object.
	 *
	 * @param string $package Package.
	 * @param string $template Template of the rule.
	 * @param string $module Name of the module to redirect to.
	 * @param string $action Name of the action to redirect to.
	 * @param array $parameters The parameters.
	 */
	public function __construct($package, $template, $module, $action, $parameters = null)
	{
		$this->module = $module;
		$this->action = $action;
		$this->initialize($package, $template, $parameters);
	}


	/**
	 * Returns the unique ID of the rule.
	 *
	 * @return string
	 */
	public final function getUniqueId()
	{
		return trim($this->module.' '.$this->action.' '.$this->m_lang);
	}


	/**
	 * Returns the module the rule will redirect to.
	 *
	 * @return string
	 */
	public final function getModule()
	{
		return $this->module;
	}


	/**
	 * Returns the action the rule will redirect to.
	 *
	 * @return string
	 */
	public final function getAction()
	{
		return $this->action;
	}
}