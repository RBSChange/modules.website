<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:change="http://www.rbs.fr/change/1.0/schema" version="1.0">
	<xsl:output indent="no" method="xml" omit-xml-declaration="no" encoding="UTF-8" cdata-section-elements="richtextcontent" />

	<xsl:template match="node()|@*">
		<xsl:copy>
			<xsl:apply-templates select="@*" />
			<xsl:apply-templates />
		</xsl:copy>
	</xsl:template>

	<!-- HTML TO XUL TEMPLATE CONVERSION -->
	<xsl:template match="div[@orient='horizontal']">
		<hbox>
			<xsl:copy-of select="@*" />
			<xsl:apply-templates />
		</hbox>
	</xsl:template>	

	<xsl:template match="div">
		<vbox>
			<xsl:copy-of select="@*" />
			<xsl:apply-templates />
		</vbox>
	</xsl:template>	
	
	<xsl:template match="a">
	</xsl:template>
		
	<xsl:template match="change:template">
		<hbox pack="center" flex="1"><vbox id="pageEditorContainer">
			<vbox>
				<xsl:copy-of select="@id" />
				<xsl:apply-templates />
			</vbox>
		</vbox></hbox>
	</xsl:template>
	
	<xsl:template match="change:content">
		<ccontent flex="1">
			<xsl:copy-of select="@*" />
			<cdroplayout flex="1" />
			<xsl:apply-templates />
		</ccontent>
	</xsl:template>

	<xsl:template match="change:layout">
		<cblocklayout flex="1">
			<xsl:attribute name="columnCount">
    			<xsl:value-of select="count(change:col)" />
 			</xsl:attribute>
			<xsl:copy-of select="@*" />
			<xsl:apply-templates select="change:col"/>
		</cblocklayout>
		<cdroplayout flex="1" />
	</xsl:template>
	
	<xsl:template match="change:col">
		<clayoutcolumn flex="1">
			<xsl:attribute name="anonid">
    			<xsl:value-of select="concat('col_',string(position()))" />
 			</xsl:attribute>
			<xsl:choose>
				<xsl:when test="position() mod 2 = 0">
					<xsl:attribute name="odd">true</xsl:attribute>
				</xsl:when>
			</xsl:choose>
			<xsl:copy-of select="@*" />
			<xsl:choose>
				<xsl:when test="node()">
					<cdropblockrow />
				</xsl:when>
				<xsl:otherwise>
					<cdropblockrow flex="1" />
				</xsl:otherwise>
			</xsl:choose>
			<xsl:apply-templates />
		</clayoutcolumn>
	</xsl:template>

	<xsl:template match="change:row">
		<clayoutrow>
			<xsl:copy-of select="@*" />
			<cdropblockcell />		
			<xsl:apply-templates />
		</clayoutrow>
		<xsl:choose>
			<xsl:when test="position()=last()">
				<cdropblockrow flex="1" />
			</xsl:when>
			<xsl:otherwise>
				<cdropblockrow />
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>

	<xsl:template match="change:block">
		<xsl:choose>
			<xsl:when test="@type='richtext'">
				<changeblock>
					<xsl:copy-of select="@*" />
					<xsl:attribute name="type">
    					<xsl:text>modules_website_staticrichtext</xsl:text>
 					</xsl:attribute>
					<xsl:value-of select="./change:richtextcontent" />
				</changeblock>
			</xsl:when>
			<xsl:otherwise>
				<changeblock>
					<xsl:copy-of select="@*" />
					<xsl:apply-templates />
				</changeblock>
			</xsl:otherwise>
		</xsl:choose>
		<cdropblockcell />
	</xsl:template>

	<xsl:template match="change:spacer">
		<cblock>
			<xsl:copy-of select="@width" />
			<xsl:copy-of select="@height" />
			<xsl:attribute name="type"><xsl:text>empty</xsl:text></xsl:attribute>
			<xsl:attribute name="bind"><xsl:text>empty</xsl:text></xsl:attribute>
		</cblock>
		<cdropblockcell />
	</xsl:template>

	<xsl:template match="change:templateblock">
		<changeblock editable="false">
			<xsl:copy-of select="@*" />
			<xsl:apply-templates />
		</changeblock>
	</xsl:template>
</xsl:stylesheet>