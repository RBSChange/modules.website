<?php
interface f_mvc_Writer
{
	/**
	 * @param string $text
	 */
	function write($text);
	
	/**
	 * @return string
	 */
	function flush();
}

class f_mvc_BufferedWriter implements f_mvc_Writer 
{	
	private $isCapturing = false;
	
	/**
	 * @see f_mvc_Writer::flush()
	 *
	 */
	function flush()
	{
		// Empty
	}
	
	/**
	 * @see f_mvc_Writer::write()
	 *
	 * @param string $text
	 */
	function write($text)
	{
		if (!$this->isCapturing)
		{
			ob_start();
			$this->isCapturing = true;
		}
		echo $text;
	}
	
	/**
	 * @return string
	 */
	function getContent()
	{
		if (!$this->isCapturing)
		{
			return "";
		}
		$this->isCapturing = false;
		return ob_get_clean();
	}
	
	function peek()
	{
		if (!$this->isCapturing)
		{
			return "";
		}
		return ob_get_contents();
	}
}