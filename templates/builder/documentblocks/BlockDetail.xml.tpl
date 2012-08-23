<?xml version="1.0" encoding="utf-8"?>
<blocks>
	<block type="modules_<{$module}>_<{$blockName}>" icon="<{$icon}>" dropModels="modules_<{$module}>/<{$documentModel->getDocumentName()}>">
		<parameters>
			<parameter name="displayMode" type="String" min-occurs="1" default-value="Success" fieldtype="dropdownlist" />
		</parameters>
		<metas>
			<meta name="label" allow="title,description" />
		</metas>
		<xul>
			<javascript>
				<constructor><![CDATA[
					this.getFields().displayMode.replaceItems({cmpref: 'modules_website/blocktemplates', blockModule: '<{$module}>', blockName: '<{$blockName}>'});
				]]></constructor>
			</javascript>
		</xul>
	</block>
</blocks>