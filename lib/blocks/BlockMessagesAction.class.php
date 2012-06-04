<?php
class website_BlockMessagesAction extends website_BlockAction
{
	/**
	 * @param website_BlockActionRequest $request
	 * @param website_BlockActionResponse $response
	 * @return String
	 */
	public function execute($request, $response)
	{
		$cfg = $this->getConfiguration();
		$flush = $cfg->getFlush();
		switch ($cfg->getShow())
		{
			case "error":
				if (website_SessionMessage::hasErrors())
				{
					foreach (website_SessionMessage::getErrors($flush) as $error)
					{
						$this->addError($error);
					}
				}
				break;
			case "message":
				if (website_SessionMessage::hasMessages())
				{
					foreach (website_SessionMessage::getMessages($flush) as $msg)
					{
						$this->addMessage($msg);
					}	
				}
				break;
			case "all":
				if (website_SessionMessage::hasMessages())
				{
					foreach (website_SessionMessage::getMessages($flush) as $msg)
					{
						$this->addMessage($msg);
					}	
				}
				if (website_SessionMessage::hasErrors())
				{
					foreach (website_SessionMessage::getErrors($flush) as $error)
					{
						$this->addError($error);
					}
				}
				break;
			default:
				throw new Exception("Bad configuration : ".$cfg->getShow());
		}
		
		if ($cfg->getClear())
		{
			website_SessionMessage::clear();
		}

		return "Success";
	}
}
