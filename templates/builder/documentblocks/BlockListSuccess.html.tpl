<!--
Available variables:
 * docs: the paginator_Paginator object containing the <{$module}>_persistentdocument_<{$documentModel->getDocumentName()}> instances
 * count: total document count
 * configuration: the <{$module}>_Block<{$blockName}>Configuration object
 * context: the website_Page object
PLEASE REMOVE THIS COMMENT WHEN YOUR TEMPLATE IS COMPLETE!
-->

<h1 change:h=""><{$documentModel->getName()}> list</h1>

<tal:block tal:condition="docs">
	<tal:block change:paginator="docs" />
	<ul class="document-list">
		<li tal:repeat="doc docs">
			<!-- TODO: specify the rendering of your item here. -->
			<a change:link="document doc">${doc/getLabelAsHtml}</a>
		</li>
	</ul>
	<tal:block change:paginator="docs" />
</tal:block>
<p tal:condition="not:docs" class="normal">${trans:m.website.paginator.no-result,ucf}</p>