<?php
class website_SessionMessage
{
	public static function addVolatileMessage($message)
	{
		$storage = change_Controller::getInstance()->getStorage();
		$sm = $storage->read('website_SessionMessage');
		if (!is_array($sm)) {$sm = array();}
		$sm['messages'][] = array(true, $message);
		$storage->write('website_SessionMessage', $sm);
	}
	
	public static function addMessage($message)
	{
		$storage = change_Controller::getInstance()->getStorage();
		$sm = $storage->read('website_SessionMessage');
		if (!is_array($sm)) {$sm = array();}
		$sm['messages'][] = array(false, $message);
		$storage->write('website_SessionMessage', $sm);		
	}
	
	public static function addVolatileError($error)
	{
		$storage = change_Controller::getInstance()->getStorage();
		$sm = $storage->read('website_SessionMessage');
		if (!is_array($sm)) {$sm = array();}
		$sm['errors'][] = array(true, $error);
		$storage->write('website_SessionMessage', $sm);
	}
	
	public static function addError($error)
	{
		$storage = change_Controller::getInstance()->getStorage();
		$sm = $storage->read('website_SessionMessage');
		if (!is_array($sm)) {$sm = array();}
		$sm['errors'][] = array(false, $error);
		$storage->write('website_SessionMessage', $sm);	
	}
	
	/**
	 * @param boolean $flush
	 * @return string[]
	 */
	public static function getMessages($flush = true)
	{
		$storage = change_Controller::getInstance()->getStorage();
		$sm = $storage->read('website_SessionMessage');
		$messages = array();
		if (!isset($sm['messages'])) {return  $messages;}
		
		if ($flush)
		{
			$newMessages = array();
			foreach ($sm['messages'] as $message)
			{
				$messages[] = $message[1];
				if (!$message[0])
				{
					$newMessages[] = $message[1];
				}
			}
			$sm['messages'] = $newMessages;
			$storage->write('website_SessionMessage', $sm);	
		}
		else
		{
			foreach ($sm['messages'] as $message)
			{
				$messages[] = $message[1];
			}
		}
		return $messages;
	}
	
	/**
	 * @return boolean
	 */
	public static function hasMessages()
	{
		$storage = change_Controller::getInstance()->getStorage();
		$sm = $storage->read('website_SessionMessage');
		return (is_array($sm) && isset($sm['messages']) && count($sm['errors']));
	}

	/**
	 * @param boolean $flush
	 * @return string[]
	 */
	public static function getErrors($flush = true)
	{
		$storage = change_Controller::getInstance()->getStorage();
		$sm = $storage->read('website_SessionMessage');
		$messages = array();
		if (!isset($sm['errors'])) {return  $messages;}
		
		if ($flush)
		{
			$newMessages = array();
			foreach ($sm['errors'] as $message)
			{
				$messages[] = $message[1];
				if (!$message[0])
				{
					$newMessages[] = $message[1];
				}
			}
			$sm['errors'] = $newMessages;
			$storage->write('website_SessionMessage', $sm);	
		}
		else
		{
			foreach ($sm['errors'] as $message)
			{
				$messages[] = $message[1];
			}
		}
		return $messages;
	}
	
	/**
	 * @return boolean
	 */
	public static function hasErrors()
	{
		$storage = change_Controller::getInstance()->getStorage();
		$sm = $storage->read('website_SessionMessage');
		return (is_array($sm) && isset($sm['errors']) && count($sm['errors']));
	}
	
	public static function clear()
	{
		$storage = change_Controller::getInstance()->getStorage();
		$sm = $storage->remove('website_SessionMessage');
	}
}