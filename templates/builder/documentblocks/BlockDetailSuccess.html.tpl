<h2 change:h="">Detail of '<em>${<{$documentModel->getDocumentName()}>/getLabelAsHtml}</em>' ; <{$documentModel->getDocumentName()}> detail block 'Success' view</h2>

<p class="normal">Edit '<em><{$successViewPath}></em>' to change this content.</p>

<dl class="normal">
<{foreach from=$documentModel->getVisiblePropertiesInfos() key=propName item=prop}>
	<!-- <{$propName}> property -->
	<dt change:translate="modules.<{$documentModel->getModuleName()}>.document.<{$documentModel->getDocumentName()}>.<{$propName|ucfirst}>" />
<{if $prop->isDocument() && !$prop->isArray()}>
<{if $prop->getType() == "modules_media/media"}>
	<!--
	Property '<{$propName}>' is a 'media/media' document,
	1. If it is a picture, uncomment the following and adjust the format if needed:
	<img <{if !$prop->isRequired()}>tal:condition="<{$documentModel->getDocumentName()}>/get<{$propName|ucfirst}>" <{/if}>change:media="document <{$documentModel->getDocumentName()}>/get<{$propName|ucfirst}>; format 'modules.media.frontoffice/thumbnail'; zoom 'true'" />
	2. If it is just a file, you should prefer the following: 
	<a <{if !$prop->isRequired()}>tal:condition="<{$documentModel->getDocumentName()}>/get<{$propName|ucfirst}>" <{/if}>change:download="document <{$documentModel->getDocumentName()}>/get<{$propName|ucfirst}>" />
	-->
<{/if}>
	<dd>
<{if $prop->getType() == "modules_list/item"}>
		<{if !$prop->isRequired()}><tal:block tal:condition="<{$documentModel->getDocumentName()}>/get<{$propName|ucfirst}>"><{/if}>${<{$documentModel->getDocumentName()}>/get<{$propName|ucfirst}>}/getLabelAsHtml}<{if !$prop->isRequired()}></tal:block><{/if}>
<{else}>
		<a <{if !$prop->isRequired()}>tal:condition="<{$documentModel->getDocumentName()}>/get<{$propName|ucfirst}>" <{/if}>change:link="document <{$documentModel->getDocumentName()}>/get<{$propName|ucfirst}>">${<{$documentModel->getDocumentName()}>/get<{$propName|ucfirst}>/getLabelAsHtml}</a>
<{/if}>
<{if !$prop->isRequired()}>
		<tal:block tal:condition="not: <{$documentModel->getDocumentName()}>/get<{$propName|ucfirst}>" change:translate="modules.<{$documentModel->getModuleName()}>.document.<{$documentModel->getDocumentName()}>.No-<{$propName}>" />
<{/if}>
	</dd>
<{elseif $prop->isDocument() && $prop->isArray()}>
	<dd>
		<ul tal:condition="<{$documentModel->getDocumentName()}>/get<{$propName|ucfirst}>Count" class="normal">
			<li tal:repeat="<{$propName|substr:0:-1}> <{$documentModel->getDocumentName()}>/get<{$propName|ucfirst}>Array">
<{if $prop->getType() == "modules_list/item"}>
				${<{$propName|substr:0:-1}>/getLabelAsHtml}
<{else}>
				<a change:link="document <{$propName}>">${<{$propName|substr:0:-1}>/getLabelAsHtml}</a>
<{/if}>
			</li>
		</ul>
		<tal:block tal:condition="not: <{$documentModel->getDocumentName()}>/get<{$propName|ucfirst}>Count" change:translate="modules.<{$documentModel->getModuleName()}>.document.<{$documentModel->getDocumentName()}>.No-<{$propName}>" />
	</dd>
<{elseif $prop->getType() == "DateTime"}>
<{if !$prop->isRequired()}>
	<dd>
		<tal:block tal:condition="<{$documentModel->getDocumentName()}>/getUI<{$propName|ucfirst}>" change:date="value <{$documentModel->getDocumentName()}>/get<{$propName|ucfirst}>; formatI18n framework.datetime.short" />
		<tal:block tal:condition="not: <{$documentModel->getDocumentName()}>/getUI<{$propName|ucfirst}>" change:translate="modules.<{$documentModel->getModuleName()}>.document.<{$documentModel->getDocumentName()}>.No-<{$propName}>" />
	</dd>
<{else}>
	<dd><tal:block change:date="value <{$documentModel->getDocumentName()}>/getUI<{$propName|ucfirst}>; formatI18n framework.datetime.short" /></dd>
<{/if}>
<{elseif $prop->getType() == "Boolean"}>
	<dd>
		<tal:block tal:condition="<{$documentModel->getDocumentName()}>/get<{$propName|ucfirst}>" change:translate="framework.boolean.True" />
		<tal:block tal:condition="not: <{$documentModel->getDocumentName()}>/get<{$propName|ucfirst}>" change:translate="framework.boolean.False" />
	</dd>
<{elseif $prop->getType() == "String" || $prop->getType() == "LongString" || $prop->getType() == "XHTMLFragment"}>
<{if !$prop->isRequired()}>
	<dd>
		<tal:block tal:condition="<{$documentModel->getDocumentName()}>/get<{$propName|ucfirst}>">${<{$documentModel->getDocumentName()}>/get<{$propName|ucfirst}>AsHtml}</tal:block>
		<tal:block tal:condition="not: <{$documentModel->getDocumentName()}>/get<{$propName|ucfirst}>" change:translate="modules.<{$documentModel->getModuleName()}>.document.<{$documentModel->getDocumentName()}>.No-<{$propName}>" />
	</dd>
<{else}>
	<dd>${<{$documentModel->getDocumentName()}>/get<{$propName|ucfirst}>AsHtml}</dd>
<{/if}>
<{else}>
<{if !$prop->isRequired()}>
	<dd>
		<tal:block tal:condition="<{$documentModel->getDocumentName()}>/get<{$propName|ucfirst}>">${<{$documentModel->getDocumentName()}>/get<{$propName|ucfirst}>}</tal:block>
		<tal:block tal:condition="not: <{$documentModel->getDocumentName()}>/get<{$propName|ucfirst}>" change:translate="modules.<{$documentModel->getModuleName()}>.document.<{$documentModel->getDocumentName()}>.No-<{$propName}>" />
	</dd>
<{else}>
	<dd>${<{$documentModel->getDocumentName()}>/get<{$propName|ucfirst}>}</dd>
<{/if}>
<{/if}>
<{/foreach}>
</dl>