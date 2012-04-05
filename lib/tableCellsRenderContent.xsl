<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" 
	xmlns:change="http://www.rbs.fr/change/1.0/schema" version="1.0">
	<xsl:output indent="no" method="xml" omit-xml-declaration="no" 
				encoding="UTF-8" cdata-section-elements="changeblock" />

	<xsl:template match="node()|@*">
		<xsl:copy>
			<xsl:apply-templates select="@*" />
			<xsl:apply-templates />
		</xsl:copy>
	</xsl:template>

	<xsl:template match="div">
		<div>
			<xsl:copy-of select="@*[not(name() = 'orient' or name() = 'flex')]"/>
			<xsl:apply-templates />
		</div>
	</xsl:template>
	
	<xsl:template match="div[not(descendant::*)]">
		<div><xsl:copy-of select="@*[not(name() = 'orient' or name() = 'flex')]"/>&#160;</div>
	</xsl:template>

	<xsl:template match="change:templates"><xsl:apply-templates /></xsl:template>

	<xsl:template match="change:template">
		<body>
			<xsl:copy-of select="@id" />
			<xsl:copy-of select="@class" />
			<xsl:apply-templates />
		</body>
	</xsl:template>

	<xsl:template match="change:content">
		<div>
			<xsl:copy-of select="@*" />
			<xsl:apply-templates />
		</div>
	</xsl:template>
	
	<xsl:template match="change:content[not(descendant::*)]"></xsl:template>

	<xsl:template match="change:layout">
		<div class="cLayout">
			<xsl:apply-templates />
		</div>
	</xsl:template>

	<xsl:template match="change:col">
		<div class="cColumn">
			<xsl:attribute name="style">
				<xsl:choose>
					<xsl:when test="@marginRight">
						<xsl:value-of select="concat('width:',string(@widthPercentage),'%; padding-right:', string(@marginRight), 'px;')" />
					</xsl:when>
					<xsl:otherwise>
						<xsl:value-of select="concat('width:',string(@widthPercentage),'%')" />
					</xsl:otherwise>
				</xsl:choose>
			</xsl:attribute>
			<xsl:apply-templates />
		</div>
	</xsl:template>

	<xsl:template match="change:row">
		<div class="cRow">
			<xsl:if test="@marginBottom">
				<xsl:attribute name="style">
					<xsl:value-of select="concat('margin-bottom:', string(@marginBottom), 'px;')" />
				</xsl:attribute>
			</xsl:if>
			<xsl:apply-templates />	
		</div>
	</xsl:template>

	<xsl:template match="change:block">
		<div class="cCell">
			<xsl:if test="@flex">
				<xsl:attribute name="style">
    				<xsl:value-of select="concat('width:',string(@flex),'%;')" />
 				</xsl:attribute>
			</xsl:if>
			<xsl:if test="@absoluteFrontofficeWidth">
				<xsl:attribute name="style">
    				<xsl:value-of select="concat('width:',string(@absoluteFrontofficeWidth),'px;')" />
 				</xsl:attribute>
			</xsl:if>
			<xsl:if test="@relativeFrontofficeWidth">
				<xsl:attribute name="style">
    				<xsl:value-of select="concat('width:',string(@relativeFrontofficeWidth),'%;')" />
 				</xsl:attribute>
			</xsl:if>
			<xsl:choose>
				<xsl:when test="@type='richtext'">
					<changeblock>
						<xsl:copy-of select="@*" />
						<xsl:attribute name="type">
    						<xsl:text>modules_website_staticrichtext</xsl:text>
 						</xsl:attribute>
 						<xsl:attribute name="class"> 						
 						   	<xsl:text>richtext</xsl:text>
 							<xsl:if test="@__class">
 								<xsl:value-of select="concat(' ', string(@__class))" />
 							</xsl:if>
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
		</div>
	</xsl:template>

	<xsl:template match="change:templateblock[@type]">
		<changeblock editable="false">
			<xsl:copy-of select="@*" />
			<xsl:apply-templates />
		</changeblock>
	</xsl:template>

	<xsl:template match="change:templateblock">
		<div style="display:none"><xsl:copy-of select="@*[name(.)!='editname']" />&#160;</div>
	</xsl:template>
</xsl:stylesheet>