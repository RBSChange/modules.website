<?php
Framework::info(__FILE__ . ' -> ' . implode(', ', $arguments));
list($websiteId, $lang, $modelName, $offset) = $arguments;
RequestContext::getInstance()->setLang($lang);
$website = website_WebsiteService::getInstance()->setCurrentWebsiteId($websiteId);
$chunkSize = 100;
$wsurs = website_UrlRewritingService::getInstance();
$tm = f_persistentdocument_TransactionManager::getInstance();
try
{
	$tm->beginTransaction();
	$query =  $tm->getPersistentProvider()->createQuery($modelName, false)
		->add(Restrictions::published())
		->addOrder(Order::asc('id'))
		->setFirstResult($offset)->setMaxResults($chunkSize);
	$documents = $query->find();
	if (count($documents) < $chunkSize)
	{
		$offset = - $offset - count($documents) -1;
	}
	else
	{
		$offset += $chunkSize;
	}

	foreach ($documents as $document) 
	{
		if ($document->getDocumentModelName() != $modelName)
		{
			//Injected model ignored;
			$offset = -1;
			break;
		}
		
		$link = $wsurs->evaluateDocumentLink($document, $website, $lang);
		if (Framework::isInfoEnabled())
		{
			if ($link === null)
			{
				Framework::info('evaluateDocumentLink ' . $document->__toString() . ' : has no url');
			}
			else
			{
				Framework::info('evaluateDocumentLink ' . $document->__toString() . ' : ' . $link->getUrl());
			}
		}
	}
	echo $offset;
	
	$tm->commit();
}
catch (Exception $e)
{
	$tm->rollBack($e);
	echo ' ' . $e->getMessage();
}
