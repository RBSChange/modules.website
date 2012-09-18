<!--
Available variables:
 * doc: the <{$module}>_persistentdocument_<{$documentModel->getDocumentName()}> instance
 * isOnDetailPage: boolean
 * configuration: the <{$module}>_Block<{$blockName}>Configuration object
 * context: the website_Page object

The document has the following properties:
<{foreach from=$documentModel->getVisiblePropertiesInfos() key=propName item=prop}>
 - <{$propName}>: <{$prop->getType()}>
<{/foreach}>
PLEASE REMOVE THIS COMMENT WHEN YOUR TEMPLATE IS COMPLETE!
-->

<h1 change:h="">${doc/getLabelAsHtml}</h1>

<!-- TODO: specify the rendering of your detail here. -->