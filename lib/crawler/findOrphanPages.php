<?php
define("WEBEDIT_HOME", realpath('.'));
require_once WEBEDIT_HOME . "/framework/Framework.php";

ini_set("memory_limit", "256M");

Controller::newInstance("controller_ChangeController");

$rc = RequestContext::getInstance();
$ps = website_PageService::getInstance();
$pp = f_persistentdocument_PersistentProvider::getInstance();

$accessiblePageIds = $ps->getTaggedPageIds();

$publishedPageIds = array();
$query = $ps->createQuery()->add(Restrictions::published())->add(Restrictions::eq('model', 'modules_website/page'))->setProjection(Projections::property('id', 'id'));
foreach ($query->find() as $row)
{
	$publishedPageIds[] = intval($row['id']);

}

$websites = website_WebsiteService::getInstance()->createQuery()->add(Restrictions::published())->find();
foreach ($websites as $website)
{
	foreach ($rc->getSupportedLanguages() as $lang)
	{
		if ($website->isLangAvailable($lang))
		{
			$crawler = new website_Crawler();
			$crawler->crawl($website, $lang);
			foreach ($crawler->getVisitedPageIds() as $id)
			{
				if (!in_array($id, $accessiblePageIds))
				{
					$accessiblePageIds[] = $id;
				}
			}
		}
	}
}

$orphanPageIds = array();
foreach (array_diff($publishedPageIds, $accessiblePageIds) as $pageId)
{
	try
	{
		$page = DocumentHelper::getDocumentInstance($pageId);
	}
	catch (Exception $e)
	{
		Framework::error(__FILE__ . " page with id = $pageId does not exist!");
	}
	$parentDocument = $page->getDocumentService()->getParentOf($page);
	$ps = f_permission_PermissionService::getInstance();
	$users = $ps->getAccessorIdsForRoleByDocumentId('modules_website.AuthenticatedFrontUser', $page->getId());
	if (count($users) == 0)
	{
		$orphanPageIds[] = $page->getId();
	}
}

$query = website_PageService::getInstance()->createQuery()->add(Restrictions::published())->add(Restrictions::eq('isorphan', true))->setProjection(Projections::property('id', 'id'));
$previousOrphanIds = array();
foreach ($query->find() as $row)
{
	$previousOrphanIds[] = intval($row['id']);
}
$pp = f_persistentdocument_PersistentProvider::getInstance();
foreach (array_diff($previousOrphanIds, $orphanPageIds) as $pageId)
{
	try
	{
		$page = DocumentHelper::getDocumentInstance($pageId);
	}
	catch (Exception $e)
	{
		Framework::error(__FILE__ . " page with id = $pageId does not exist!");
	}
	$page->setIsorphan(false);
	$pp->updateDocument($page);
}

foreach ($orphanPageIds as $pageId)
{
	try
	{
		$page = DocumentHelper::getDocumentInstance($pageId);
	}
	catch (Exception $e)
	{
		Framework::error(__FILE__ . " page with id = $pageId does not exist!");
	}
	$page->setIsorphan(true);
	$pp->updateDocument($page);
}