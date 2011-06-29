<?php
class website_BlockActionResponse implements f_mvc_Response
{
	/**
	 * @var f_mvc_TextWriter
	 */
	private $writer;
	
	/**
	 * @var Boolean
	 */
	private $committed = false;
	
	/**
	 * @see f_mvc_Response::getWriter()
	 *
	 * @return f_mvc_BufferedWriter
	 */
	function getWriter()
	{
		if ($this->writer === null)
		{
			$this->writer = new f_mvc_BufferedWriter();
		}
		return $this->writer;
	}
	
	/**
	 * @see f_mvc_Response::setContentType()
	 *
	 */
	function setContentType($type)
	{
		// Empty 
	}
	
	/**
	 * @see f_mvc_Response::setHeader()
	 *
	 */
	function setHeader($name, $value)
	{
		// Empty
	}
	
	/**
	 * @see f_mvc_Response::setStatus()
	 *
	 */
	function setStatus($value)
	{
		// Empty;
	}
	
	/**
	 * @see f_mvc_Response::getContentType()
	 *
	 * @return String
	 */
	function getContentType()
	{
		return "text/html";
	}

	/**
	 * @see f_mvc_Response::isCommitted()
	 *
	 * @return Boolean
	 */
	function isCommitted()
	{
		return false;
	}
	
	/**
	 * @see f_mvc_Response::reset()
	 */
	function reset()
	{
		$this->writer = new f_mvc_BufferedWriter();
	}

	/**
	 * @see f_mvc_Response::write()
	 *
	 * @param String $string
	 */
	function write($string)
	{
		$this->getWriter()->write($string);
	}
}