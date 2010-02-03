<?php
class website_lib_urlrewriting_TaggedPageRule
	extends website_lib_urlrewriting_Rule
{

	/**
	 * Tag.
	 *
	 * @var string
	 */
	protected $pageTag = null;


	/**
	 * Indicates if the rule is bound to the exclusive or contextual $tag or not.
	 *
	 * @param string $tag
	 * @return boolean
	 */
	public function hasTag($tag)
	{
		return $this->pageTag === $tag;
	}


	/**
	 * Builds the rule object.
	 *
	 * @param string $package Package.
	 * @param string $template Template of the rule.
	 * @param string $pageTag Tag of the page.
	 * @param array $parameters The parameters.
	 */
	public function __construct($package, $template, $pageTag, $parameters = null)
	{
		$this->pageTag = $pageTag;
		$this->initialize($package, $template, $parameters);
	}


	/**
	 * Returns the unique ID of the rule.
	 *
	 * @return string
	 */
	public function getUniqueId()
	{
		return trim($this->pageTag.' '.$this->m_lang.' '.$this->getCondition());
	}


	/**
	 * Returns the tag.
	 *
	 * @return string
	 */
	public function getPageTag()
	{
		return $this->pageTag;
	}
}