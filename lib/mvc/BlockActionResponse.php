<?php
interface f_mvc_Response
{
	/**
	 * Set the HTTP header $name to value $value
	 * 
	 * @param string $name
	 * @param string $value
	 */
	function setHeader($name, $value);
	
	/**
	 * Set the HTTP status to value
	 * 
	 * @param string $value
	 */
	function setStatus($value);
	
	/**
	 * @param string $value
	 */
	function setContentType($value);
	
	/**
	 * @return string
	 */
	function getContentType();
	
	/**
	 * tells wether or not the response has been committed
	 * 
	 * @return boolean
	 */
	function isCommitted();
	
	/**
	 * Reset the response
	 */
	function reset();
	
	/**
	 * @return f_mvc_Writer
	 */
	function getWriter();
	
	/**
	 * @param string
	 */
	function write($string);
}

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
	 * @return string
	 */
	function getContentType()
	{
		return "text/html";
	}

	/**
	 * @see f_mvc_Response::isCommitted()
	 *
	 * @return boolean
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
	 * @param string $string
	 */
	function write($string)
	{
		$this->getWriter()->write($string);
	}
}