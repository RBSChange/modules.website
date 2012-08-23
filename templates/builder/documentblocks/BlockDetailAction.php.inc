<?php 
/* @var $this builder_DocumentBlockGenerator */
$blockName = ucfirst($this->documentModel->getDocumentName());
$this->setBlockIcon($this->documentModel->getIcon());
$genTag = false;