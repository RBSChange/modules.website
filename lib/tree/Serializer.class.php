<?php
class website_tree_Serializer
{
	/**
	 * @var XMLWriter
	 */
	private $output;
	
	public $command;
	public $resourceType;
	public $currentFolder;
	public $currentFolderKey;
	public $error;
	
	public function __construct()
	{
		$this->output = new XMLWriter();
		$this->output->openMemory();
	}
	
	/**
	 * @param website_tree_NodeList
	 * @return string
	 */
	public function serialize($nodeList)
	{
		$this->serializeHeader();
		
		if ($nodeList !== NULL)
		{
			$this->output->startElement('Folders');
			foreach ($nodeList as $node) 
			{
				if ($node->hasChildren())
				{
					$this->serializeNodeFolder($node);	
				}
			}
			$this->output->endElement();
			
			$this->output->startElement('Files');
			foreach ($nodeList as $node) 
			{
				if (!$node->hasChildren())
				{
					$this->serializeNodeFile($node);	
				}
			}
			$this->output->endElement();
		}
		$this->serializeFooter();
		
		return $this->output->outputMemory(true);
	}
	
	private function serializeHeader()
	{
		$this->output->startElement('Connector');
		
		$this->output->writeAttribute('command', $this->command);
		$this->output->writeAttribute('resourceType', $this->resourceType);

		$this->output->startElement('CurrentFolder');
		$this->output->writeAttribute('path', $this->currentFolder);
		$this->output->writeAttribute('key', $this->currentFolderKey);
		$this->output->endElement();
		
		if ($this->error != null)
		{
			$this->output->startElement('Error');
			$this->output->writeAttribute('number', $this->error);
			$this->output->endElement();
		}
	}
	
	private function serializeFooter()
	{		
		$this->output->endElement();
	}	
	/**
	 * @param website_tree_FileNode $node
	 */
	private function serializeNodeFile($node)
	{
		$this->output->startElement('File');
		$this->output->writeAttribute('key', $node->getId());
		
		$this->output->writeAttribute('name', $node->getFilenameAttribute());
		$this->output->writeAttribute('ext', $node->getExtensionAttribute());
		$this->output->writeAttribute('size', $node->getSizeAttribute());
		$this->output->writeAttribute('url', $node->getUrlAttribute());
		$this->output->writeAttribute('title', $node->getTitleAttribute());
		$this->output->writeAttribute('label', $node->getLabelAttribute());
				
		$this->output->endElement();
	}
	
	/**
	 * @param website_tree_FolderNode $node
	 */
	private function serializeNodeFolder($node)
	{
		$this->output->startElement('Folder');
		$this->output->writeAttribute('key', $node->getId());
		$this->output->writeAttribute('name', $node->getLabelAttribute());	
		$this->output->endElement();
	}
}