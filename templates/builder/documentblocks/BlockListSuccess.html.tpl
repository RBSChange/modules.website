<h2 change:h="">List of '<em><{$documentModel->getDocumentName()}></em>' documents</h2>

<p class="normal">Edit '<em><{$successViewPath}></em>' to change this content.</p>

<div change:paginator="<{$documentModel->getDocumentName()}>s"/>

<p class="normal">
	<strong change:translate="modules.<{$documentModel->getModuleName()}>.frontoffice.Order-by" />
	<a tal:condition="php: orderBy != 'label'" change:currentPageLink="<{$documentModel->getModuleName()}>Param[orderBy] 'label'; <{$documentModel->getModuleName()}>Param[page] '1'"
		change:translate="modules.<{$documentModel->getModuleName()}>.document.<{$documentModel->getDocumentName()}>.label" />
	<span tal:condition="php: orderBy == 'label'"
		change:translate="modules.<{$documentModel->getModuleName()}>.document.<{$documentModel->getDocumentName()}>.label" />.
	<br/>
	<strong change:translate="modules.<{$documentModel->getModuleName()}>.frontoffice.Order-by-direction" />
	<a tal:condition="php: orderByDirection != 'asc'" change:currentPageLink="<{$documentModel->getModuleName()}>Param[orderByDirection] 'asc'; <{$documentModel->getModuleName()}>Param[page] '1'"
		change:translate="modules.<{$documentModel->getModuleName()}>.frontoffice.order-by-asc" />
	<span tal:condition="php: orderByDirection == 'asc'"
		change:translate="modules.<{$documentModel->getModuleName()}>.frontoffice.order-by-asc" />
	|
	<a tal:condition="php: orderByDirection != 'desc'" change:currentPageLink="<{$documentModel->getModuleName()}>Param[orderByDirection] 'desc'; <{$documentModel->getModuleName()}>Param[page] '1'"
		change:translate="modules.<{$documentModel->getModuleName()}>.frontoffice.order-by-desc" />
	<span tal:condition="php: orderByDirection == 'desc'"
		change:translate="modules.<{$documentModel->getModuleName()}>.frontoffice.order-by-desc" />.
</p>

<ul class="normal">
	<li tal:repeat="<{$documentModel->getDocumentName()}> <{$documentModel->getDocumentName()}>s">
		<a change:link="document <{$documentModel->getDocumentName()}>">${<{$documentModel->getDocumentName()}>/getLabelAsHtml}</a>
	</li>
</ul>

<div change:paginator="<{$documentModel->getDocumentName()}>s"/>