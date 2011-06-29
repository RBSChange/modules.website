<?php
class website_SessionMessage
{
	public static function addVolatileMessage($message)
	{
		$_SESSION['website_SessionMessage']['messages'][] = array(true, $message);
	}
	
	public static function addMessage($message)
	{
		$_SESSION['website_SessionMessage']['messages'][] = array(false, $message);
	}
	
	public static function addVolatileError($error)
	{
		$_SESSION['website_SessionMessage']['errors'][] = array(true, $error);
	}
	
	public static function addError($error)
	{
		$_SESSION['website_SessionMessage']['errors'][] = array(false, $error);
	}
	
	public static function getMessages($flush = true)
	{
		$messages = array();
		if ($flush)
		{
			$newMessages = array();
			foreach ($_SESSION['website_SessionMessage']['messages'] as $message)
			{
				$messages[] = $message[1];
				if (!$message[0])
				{
					$newMessages = $message[1];
				}
			}
			$_SESSION['website_SessionMessage']['messages'] = $newMessages;
		}
		else
		{
			foreach ($_SESSION['website_SessionMessage']['messages'] as $message)
			{
				$messages[] = $message[1];
			}
		}
		return $messages;
	}
	
	public static function hasMessages()
	{
		return isset($_SESSION['website_SessionMessage']['messages'])
			&& f_util_ArrayUtils::isNotEmpty($_SESSION['website_SessionMessage']['messages']);
	}
	
	public static function getErrors($flush = true)
	{
		$messages = array();
		if ($flush)
		{
			$newMessages = array();
			foreach ($_SESSION['website_SessionMessage']['errors'] as $message)
			{
				$messages[] = $message[1];
				if (!$message[0])
				{
					$newMessages = $message[1];
				}
			}
			$_SESSION['website_SessionMessage']['errors'] = $newMessages;
		}
		else
		{
			foreach ($_SESSION['website_SessionMessage']['errors'] as $message)
			{
				$messages[] = $message[1];
			}
		}
		return $messages;
	}
	
	public static function hasErrors()
	{
		return isset($_SESSION['website_SessionMessage']['errors'])
			&& f_util_ArrayUtils::isNotEmpty($_SESSION['website_SessionMessage']['errors']);
	}
	
	public static function clear()
	{
		unset($_SESSION['website_SessionMessage']['errors']);
	}
}