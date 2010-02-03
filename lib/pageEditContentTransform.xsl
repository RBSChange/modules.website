<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:change="http://www.rbs.fr/change/1.0/schema" version="1.0">
	<xsl:output indent="no" method="xml" omit-xml-declaration="no" encoding="UTF-8" cdata-section-elements="richtextcontent" />

	<xsl:template match="node()|@*">
		<xsl:copy>
			<xsl:apply-templates select="@*" />
			<xsl:apply-templates />
		</xsl:copy>
	</xsl:template>

	<xsl:template match="change:template">
		<hbox pack="center" flex="1"><vbox id="pageEditorContainer">
			<div>
				<xsl:copy-of select="@id" />
				<div>
					<xsl:apply-templates />
				</div>
			</div>
		</vbox></hbox>
	</xsl:template>

	<xsl:template match="change:content">
		<vbox>
			<xsl:copy-of select="@*" />
			<clayoutdropzone flex="1" type="bottom" />
			<xsl:apply-templates />
		</vbox>
	</xsl:template>

	<xsl:template match="change:layout">
		<clayout flex="1">
			<xsl:attribute name="columnCount">
    			<xsl:value-of select="count(change:col)" />
 			</xsl:attribute>
			<xsl:copy-of select="@*" />
			<xsl:apply-templates />
		</clayout>
		<clayoutdropzone flex="1" type="bottom" />
	</xsl:template>

	<xsl:template match="change:col">
		<clayoutelement collapsed="true">
			<xsl:attribute name="anonid">
    			<xsl:value-of select="concat('col_',string(position()))" />
 			</xsl:attribute>
			<xsl:choose>
				<xsl:when test="position() mod 2 = 0">
					<xsl:attribute name="class">
    					<xsl:value-of select="'odd'" />
 					</xsl:attribute>
				</xsl:when>
			</xsl:choose>
			<xsl:copy-of select="@*" />
			<xsl:choose>
				<xsl:when test="node()">
					<cdropzone type="bottom" />
				</xsl:when>
				<xsl:otherwise>
					<cdropzone type="bottom" flex="1" />
				</xsl:otherwise>
			</xsl:choose>
			<xsl:apply-templates />
		</clayoutelement>
	</xsl:template>

	<xsl:template match="change:row">
		<hbox>
			<xsl:copy-of select="@*" />
			<xsl:apply-templates />
		</hbox>
		<xsl:choose>
			<xsl:when test="position()=last()">
				<cdropzone type="bottom" flex="1" />
			</xsl:when>
			<xsl:otherwise>
				<cdropzone type="bottom" />
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
	</xsl:template>

	<xsl:template match="change:templateblock">
		<hbox>
			<changeblock editable="false">
				<xsl:copy-of select="@*" />
				<xsl:apply-templates />
			</changeblock>
		</hbox>
	</xsl:template>

	<xsl:template match="change:spacer">
		<cemptyblock type="empty">
			<xsl:copy-of select="@*" />
			<xsl:apply-templates />
		</cemptyblock>
	</xsl:template>

</xsl:stylesheet>