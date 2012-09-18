<?php
/**
 * @package modules.<{$module}>
 * @method <{$module}>_Block<{$blockName}>Configuration getConfiguration()
 */
class <{$module}>_Block<{$blockName}>Action extends website_BlockAction
{
	/**
	 * @var <{$module}>_persistentdocument_<{$documentModel->getDocumentName()}>|null
	 */
	protected $published<{$documentModel->getDocumentName()|ucfirst}> = false;
	
	/**
	 * @return <{$module}>_persistentdocument_<{$documentModel->getDocumentName()}>|null
	 */
	protected function getPublished<{$documentModel->getDocumentName()|ucfirst}>()
	{
		if ($this->published<{$documentModel->getDocumentName()|ucfirst}> === false)
		{
			$doc = DocumentHelper::getDocumentInstanceIfExists($this->getDocumentIdParameter());
			if ($doc instanceof <{$module}>_persistentdocument_<{$documentModel->getDocumentName()}> && $doc->isPublished())
			{
				$this->published<{$documentModel->getDocumentName()|ucfirst}> = $doc;
			}
			else
			{
				$this->published<{$documentModel->getDocumentName()|ucfirst}> = null;
			}
		}
		return $this->published<{$documentModel->getDocumentName()|ucfirst}>;
	}
	
	/**
	 * @param f_mvc_Request documentquest
	 * @param f_mvc_Response documentsponse
	 * @return String
	 */
	public function execute($request, $response)
	{
		if ($this->isInBackofficeEdition())
		{
			return website_BlockView::NONE;
		}
		
		$document = $this->getPublished<{$documentModel->getDocumentName()|ucfirst}>();
		$isOnDetailPage = $this->isOnDetailPage($document);
		$request->setAttribute('isOnDetailPage', $isOnDetailPage);
		if ($document === null)
		{
			if ($isOnDetailPage && !$this->isInBackofficePreview())
			{
				HttpController::getInstance()->redirect('website', 'Error404');
			}
			return website_BlockView::NONE;
		}
		$request->setAttribute('doc', $document);
		
		return $this->getConfiguration()->getDisplayMode();
	}
	
	/**
	 * @return array<String, String>
	 */
	public function getMetas()
	{
		$doc = $this->getPublished<{$documentModel->getDocumentName()|ucfirst}>();
		if ($doc !== null)
		{
			return array('label' => $doc->getNavigationLabel());
		}
		return array();
	}
	
	/**
	 * @param <{$module}>_persistentdocument_<{$documentModel->getDocumentName()}> $document
	 * @return boolean
	 */
	protected function isOnDetailPage($document)
	{
		return $document !== null && $document->getId() == $this->getContext()->getDetailDocumentId();
	}
}