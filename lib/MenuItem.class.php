<?php
/**
 * @date Wed Jun 06 12:09:43 CEST 2007
 * @author intbonjf

 * This class represents a menu entry (item) ready to be used by the template
 * engine. It contains all the useful information to build the link to the
 * resource represented by this website_MenuItem.
 *
 */
class website_MenuItem
{
	private $id;
	private $label;
	private $description;
	private $documentModelName;
	private $url;
	private $visibility = 3;
	private $level = 0;
	private $popup = false;
	private $popupParameters = array();
	private $onClick;
	private $type;

	const TYPE_TOPIC = 1;
	const TYPE_PAGE = 2;

    /**
     * @param integer $id
     * @return website_MenuItem
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     * @return website_MenuItem
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return Boolean
     */
    public function isTopic()
    {
        return $this->type === self::TYPE_TOPIC;
    }

    /**
     * @return Boolean
     */
    public function isPage()
    {
        return $this->type === self::TYPE_PAGE;
    }

    /**
     * @param string $label
     * @return website_MenuItem
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $description
     * @return website_MenuItem
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $documentModelName
     * @return website_MenuItem
     */
    public function setDocumentModelName($documentModelName)
    {
        $this->documentModelName = $documentModelName;
        return $this;
    }

    /**
     * @return string
     */
    public function getDocumentModelName()
    {
        return $this->documentModelName;
    }

    /**
     * @param string $url
     * @return website_MenuItem
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return boolean
     */
    public function hasUrl()
    {
        return $this->url !== null;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url !== null ? $this->url : website_WebsiteModuleService::EMPTY_URL;
    }

    /**
     * @param string $onClick
     * @return website_MenuItem
     */
    public function setOnClick($onClick)
    {
        $this->onClick = $onClick;
        return $this;
    }

    /**
     * @return string
     */
    public function getOnClick()
    {
        return $this->onClick;
    }

    /**
     * @param integer $level
     * @return website_MenuItem
     */
    public function setLevel($level)
    {
        $this->level = $level;
        return $this;
    }

    /**
     * @return integer
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param integer $visibility
     * @return website_MenuItem
     */
    public function setNavigationVisibility($visibility)
    {
        $this->visibility = $visibility;
        return $this;
    }

    /**
     * @return integer
     */
    public function getNavigationVisibility()
    {
        return $this->visibility;
    }

    /**
     * @return boolean
     */
    public function getPopup()
    {
    	return $this->popup;
    }

    /**
     * @param boolean $bool
     * @return website_MenuItem
     */
    public function setPopup($bool)
    {
    	$this->popup = (bool)$bool;
    	return $this;
    }

    /**
     * @return array
     */
    public function getPopupParameters()
    {
    	if ( ! isset($this->popupParameters['width']) || ! $this->popupParameters['width'])
    	{
    		$this->popupParameters['width'] = null;
    	}
    	if ( ! isset($this->popupParameters['height']) || ! $this->popupParameters['height'])
    	{
    		$this->popupParameters['height'] = null;
    	}
    	return $this->popupParameters;
    }

    /**
     * @return integer
     */
    public function getPopupWidth()
    {
    	return isset($this->popupParameters['width']) ? intval($this->popupParameters['width']) : null;
    }

    /**
     * @return integer
     */
    public function getPopupHeight()
    {
    	return isset($this->popupParameters['height']) ? intval($this->popupParameters['height']) : null;
    }

    /**
     * @param array $parameters
     * @return website_MenuItem
     */
    public function setPopupParameters($parameters)
    {
    	if ( is_array($parameters) )
    	{
    		$this->popupParameters = $parameters;
    	}
    	else if ( is_string($parameters) && strlen($parameters) > 0 )
    	{
    		$this->popupParameters = array();
    		$paramArray = explode(',', $parameters);
    		foreach ($paramArray as $p)
    		{
    			list($n, $v) = explode(':', $p);
    			$this->popupParameters[trim($n)] = trim($v);
    		}
    	}
    	return $this;
    }
}
