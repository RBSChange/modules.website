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

	<xsl:template match="change:templates"><xsl:apply-templates /></xsl:template>

	<xsl:template match="change:template">
		<body>
			<xsl:copy-of select="@id" />
			<div>
				<xsl:apply-templates />
			</div>
		</body>
	</xsl:template>

	<xsl:template match="change:content">
		<div>
			<xsl:copy-of select="@*" />
			<xsl:apply-templates />
		</div>
	</xsl:template>

	<xsl:template match="change:layout">
		<div>
			<xsl:apply-templates />
		</div>
		<div class="cleaner"/>
	</xsl:template>

	<xsl:template match="change:col">
		<div class="freeColumn">
			<xsl:attribute name="class">
				<xsl:choose>
					<xsl:when test="position()=last() and position()=1">
						<xsl:text>freeColumn</xsl:text>
					</xsl:when>
					<xsl:otherwise>
						<xsl:choose>
							<xsl:when test="position() mod 2 = 0">
    							<xsl:text>freeColumn right</xsl:text>
							</xsl:when>
							<xsl:otherwise>
								<xsl:text>freeColumn left</xsl:text>
							</xsl:otherwise>
						</xsl:choose>
					</xsl:otherwise>
				</xsl:choose>			
			</xsl:attribute>
			<xsl:attribute name="style">
				<xsl:choose>
					<xsl:when test="position()=last() and position()=1">
						<xsl:value-of select="concat('width:',string(@widthPercentage),'%')" />
					</xsl:when>
					<xsl:otherwise>
						<xsl:choose>
							<xsl:when test="position() mod 2 = 0">
    							<xsl:value-of select="concat('width:',string(@widthPercentage - 0.5),'%; float:left; display:inline;')" />
							</xsl:when>
							<xsl:otherwise>
								<xsl:value-of select="concat('width:',string(@widthPercentage - 0.5),'%; float:left; display:inline;')" />
						</xsl:otherwise>
						</xsl:choose>
					</xsl:otherwise>
				</xsl:choose>
				
			</xsl:attribute>
			<!--  xsl:copy-of select="@*" /-->
			<div class="freeMargin">
			<xsl:apply-templates />
			</div>	
		</div>
	</xsl:template>

	<xsl:template match="change:row">
		<div class="freeLocation">
			<xsl:copy-of select="@*" />
			<xsl:apply-templates />
		</div>
	</xsl:template>

	<xsl:template match="change:block">
		<div class="freeBlock">
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

	<xsl:template match="change:templateblock">
		<changeblock editable="false">
			<xsl:copy-of select="@*" />
			<xsl:apply-templates />
		</changeblock>
	</xsl:template>

	<xsl:template match="change:spacer">
		<div class="freeBlock">
			<xsl:if test="@absoluteFrontofficeWidth and @absoluteFrontofficeHeight">
				<xsl:attribute name="style">
    				<xsl:value-of select="concat('width:',string(@absoluteFrontofficeWidth),'px; height: ', string(@absoluteFrontofficeHeight),'px;display:inline;')" />
 				</xsl:attribute>
			</xsl:if>
			<xsl:if test="@absoluteFrontofficeWidth and not(@absoluteFrontofficeHeight)">
				<xsl:attribute name="style">
    				<xsl:value-of select="concat('width:',string(@absoluteFrontofficeWidth),'px;display:inline;')" />
 				</xsl:attribute>
			</xsl:if>
			<xsl:if test="not(@absoluteFrontofficeWidth) and @absoluteFrontofficeHeight">
				<xsl:attribute name="style">
    				<xsl:value-of select="concat('width:',string(@relativeFrontofficeWidth),'%;','height:',string(@absoluteFrontofficeHeight),'px;display:inline;')" />
 				</xsl:attribute>
			</xsl:if>
			<xsl:if test="not(@absoluteFrontofficeWidth) and not(@absoluteFrontofficeHeight)">
				<xsl:attribute name="style">
    				<xsl:value-of select="concat('width:',string(@relativeFrontofficeWidth),'%;display:inline;')" />
 				</xsl:attribute>
			</xsl:if>
			<div class="empty" />
		</div>
	</xsl:template>
</xsl:stylesheet>