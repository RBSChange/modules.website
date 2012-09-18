<?xml version="1.0" encoding="utf-8"?>
<blocks>
	<block type="modules_<{$module}>_<{$blockName}>" icon="<{$icon}>">
		<parameters>
			<parameter name="displayMode" type="String" min-occurs="1" default-value="Success" fieldtype="dropdownlist" />
			<parameter name="itemsPerPage" type="Integer" min-occurs="1" default-value="10">
				<constraints>min:1;max:100</constraints>
			</parameter>
		</parameters>
		<xul>
			<javascript>
				<constructor><![CDATA[
					this.getFields().displayMode.replaceItems({cmpref: 'modules_website/blocktemplates', blockModule: '<{$module}>', blockName: '<{$blockName}>'});
				]]></constructor>
			</javascript>
		</xul>
	</block>
</blocks>