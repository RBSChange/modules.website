<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform" 
	xmlns:php="http://php.net/xsl">
	<xsl:output indent="no" method="xml" omit-xml-declaration="yes" encoding="UTF-8" />
	<xsl:template match="/">
		<body>
			<xsl:apply-templates />
		</body>
	</xsl:template>

	<xsl:template match="comment()"></xsl:template>
	<xsl:template match="style"></xsl:template>
	<xsl:template match="header"></xsl:template>
	<xsl:template match="script"></xsl:template>
	
	<xsl:template match="*">
		<xsl:apply-templates/>
	</xsl:template>
	
	<xsl:template match="span[@id='cursor']" priority="10"><span id="cursor">CURSOR</span></xsl:template>
	
	<xsl:template match="p[normalize-space(.) ='' and not(descendant::hr) and not(descendant::br) and not(descendant::img)]" priority="5"></xsl:template>
	<xsl:template match="p[@style='text-align: left;' or @class='text-align-left' or @class='normal text-align-left']"><p class="normal text-align-left"><xsl:apply-templates /></p></xsl:template>
	<xsl:template match="p[@style='text-align: center;' or @class='text-align-center' or @class='normal text-align-center']"><p class="normal text-align-center"><xsl:apply-templates /></p></xsl:template>
	<xsl:template match="p[@style='text-align: right;' or @class='text-align-right' or @class='normal text-align-right']"><p class="normal text-align-right"><xsl:apply-templates /></p></xsl:template>
	<xsl:template match="p[@style='text-align: justify;' or @class='text-align-justify' or @class='normal text-align-justify']"><p class="normal text-align-justify"><xsl:apply-templates /></p></xsl:template>
	<xsl:template match="p"><p class="normal"><xsl:apply-templates /></p></xsl:template>
		
	<xsl:template match="br"><br /></xsl:template>
	<xsl:template match="hr"><hr width="100%" size="2" /></xsl:template>
	<xsl:template match="hr[@class='clear-both']"><hr class="clear-both" /></xsl:template>	
	
	<xsl:template match="em[parent::em]"><xsl:apply-templates /></xsl:template>
	<xsl:template match="em[normalize-space(.) ='' and not(descendant::hr) and not(descendant::br) and not(descendant::img)]" priority="5"></xsl:template>
	<xsl:template match="em"><em><xsl:apply-templates /></em></xsl:template>
	
	<xsl:template match="i[parent::i or parent::em]"><xsl:apply-templates /></xsl:template>
	<xsl:template match="i[normalize-space(.) ='' and not(descendant::hr) and not(descendant::br) and not(descendant::img)]" priority="5"></xsl:template>
	<xsl:template match="i"><em><xsl:apply-templates /></em></xsl:template>	
	
	<xsl:template match="strong[parent::strong]"><xsl:apply-templates /></xsl:template>
	<xsl:template match="strong[normalize-space(.) ='' and not(descendant::hr) and not(descendant::br) and not(descendant::img)]" priority="5"></xsl:template>
	<xsl:template match="strong"><strong><xsl:apply-templates /></strong></xsl:template>
	
	<xsl:template match="b[parent::b or parent::strong]"><xsl:apply-templates /></xsl:template>
	<xsl:template match="b[normalize-space(.) ='' and not(descendant::hr) and not(descendant::br) and not(descendant::img)]" priority="5"></xsl:template>
	<xsl:template match="b"><strong><xsl:apply-templates /></strong></xsl:template>
	
	<xsl:template match="span[@class='underline']"><span class="underline"><xsl:apply-templates /></span></xsl:template>
	
	<xsl:template match="u[parent::u]"><xsl:apply-templates /></xsl:template>
	<xsl:template match="u[normalize-space(.) ='' and not(descendant::hr) and not(descendant::br) and not(descendant::img)]" priority="5"></xsl:template>
	<xsl:template match="u"><span class="underline"><xsl:apply-templates /></span></xsl:template>
	
	<xsl:template match="del[parent::del]"><xsl:apply-templates /></xsl:template>
	<xsl:template match="del[normalize-space(.) ='' and not(descendant::hr) and not(descendant::br) and not(descendant::img)]" priority="5"></xsl:template>
	<xsl:template match="del"><del><xsl:apply-templates /></del></xsl:template>
	
	<xsl:template match="strike[parent::strike or parent::del]"><xsl:apply-templates /></xsl:template>
	<xsl:template match="strike[normalize-space(.) ='' and not(descendant::hr) and not(descendant::br) and not(descendant::img)]" priority="5"></xsl:template>
	<xsl:template match="strike"><del><xsl:apply-templates /></del></xsl:template>
	
	<xsl:template match="sub[normalize-space(.) ='' and not(descendant::hr) and not(descendant::br) and not(descendant::img)]" priority="5"></xsl:template>
	<xsl:template match="sub"><sub><xsl:apply-templates /></sub></xsl:template>
	
	<xsl:template match="sup[normalize-space(.) ='' and not(descendant::hr) and not(descendant::br) and not(descendant::img)]" priority="5"></xsl:template>
	<xsl:template match="sup"><sup><xsl:apply-templates /></sup></xsl:template>
	
	<xsl:template match="h1[normalize-space(.) ='' and not(descendant::*)]" priority="5"></xsl:template>
	<xsl:template match="h1">
		<h1>
			<xsl:attribute name="class">
				<xsl:value-of select="php:function('website_XHTMLCleanerHelper::safeHClass', .)" />
			</xsl:attribute> 
		<xsl:apply-templates />
		</h1>
	</xsl:template>
	
	<xsl:template match="h2[normalize-space(.) ='' and not(descendant::*)]" priority="5"></xsl:template>
	<xsl:template match="h2">
		<h2>
			<xsl:attribute name="class">
				<xsl:value-of select="php:function('website_XHTMLCleanerHelper::safeHClass', .)" />
			</xsl:attribute> 
		<xsl:apply-templates />
		</h2>
	</xsl:template>
	
	<xsl:template match="h3[normalize-space(.) ='' and not(descendant::*)]" priority="5"></xsl:template>
	<xsl:template match="h3">
		<h3>
			<xsl:attribute name="class">
				<xsl:value-of select="php:function('website_XHTMLCleanerHelper::safeHClass', .)" />
			</xsl:attribute> 
		<xsl:apply-templates />
		</h3>
	</xsl:template>
	
	<xsl:template match="h4[normalize-space(.) ='' and not(descendant::*)]" priority="5"></xsl:template>
	<xsl:template match="h4">
		<h4>
			<xsl:attribute name="class">
				<xsl:value-of select="php:function('website_XHTMLCleanerHelper::safeHClass', .)" />
			</xsl:attribute> 
		<xsl:apply-templates />
		</h4>
	</xsl:template>
	
	<xsl:template match="h5[normalize-space(.) ='' and not(descendant::*)]" priority="5"></xsl:template>
	<xsl:template match="h5">
		<h5>
			<xsl:attribute name="class">
				<xsl:value-of select="php:function('website_XHTMLCleanerHelper::safeHClass', .)" />
			</xsl:attribute> 
		<xsl:apply-templates />
		</h5>
	</xsl:template>
	
	<xsl:template match="h6[normalize-space(.) ='' and not(descendant::*)]" priority="5"></xsl:template>
	<xsl:template match="h6">
		<h6>
			<xsl:attribute name="class">
				<xsl:value-of select="php:function('website_XHTMLCleanerHelper::safeHClass', .)" />
			</xsl:attribute> 
		<xsl:apply-templates />
		</h6>
	</xsl:template>
	
	<xsl:template match="ol[normalize-space(.) ='' and not(descendant::hr) and not(descendant::br) and not(descendant::img)]" priority="5"></xsl:template>	
	<xsl:template match="ol"><ol class="normal"><xsl:apply-templates /></ol></xsl:template>
	
	<xsl:template match="ul[normalize-space(.) ='' and not(descendant::hr) and not(descendant::br) and not(descendant::img)]" priority="5"></xsl:template>	
	<xsl:template match="ul"><ul class="normal"><xsl:apply-templates /></ul></xsl:template>
	
	<xsl:template match="li[normalize-space(.) ='' and not(descendant::hr) and not(descendant::br) and not(descendant::img)]" priority="5"></xsl:template>	
	<xsl:template match="li"><li><xsl:apply-templates /></li></xsl:template>

	<xsl:template match="blockquote[normalize-space(.) ='' and not(descendant::hr) and not(descendant::br) and not(descendant::img)]" priority="5"></xsl:template>	
	<xsl:template match="blockquote"><blockquote><xsl:apply-templates /></blockquote></xsl:template>

	<xsl:template match="table">
		<table class="normal">
			<xsl:copy-of select="@*[name() = 'summary']"/>
			<xsl:apply-templates />
		</table>
	</xsl:template>
	
	<xsl:template match="caption[normalize-space(.) ='' and not(descendant::hr) and not(descendant::br) and not(descendant::img)]" priority="5"></xsl:template>
	<xsl:template match="caption"><caption><xsl:apply-templates /></caption></xsl:template>
	<xsl:template match="thead"><thead><xsl:apply-templates /></thead></xsl:template>
	<xsl:template match="tbody"><tbody><xsl:apply-templates /></tbody></xsl:template>
	<xsl:template match="tr"><tr><xsl:apply-templates /></tr></xsl:template>
	<xsl:template match="th"><th><xsl:copy-of select="@*[name() = 'id' or name() = 'abbr' or name() = 'class']"/><xsl:apply-templates /></th></xsl:template>
	<xsl:template match="td"><td><xsl:copy-of select="@*[name() = 'rowspan' or name() = 'colspan' or name() = 'headers' or name() = 'class']"/><xsl:apply-templates /></td></xsl:template>
	
	<xsl:template match="span[normalize-space(.) ='' and not(descendant::hr) and not(descendant::br) and not(descendant::img)]" priority="5"></xsl:template>	
	<xsl:template match="span[@class='lang']">
		<span>
			<xsl:copy-of select="@*[name() = 'lang' or name() = 'class']"/>
			<xsl:apply-templates />
		</span>
	</xsl:template>
	
	<xsl:template match="abbr[normalize-space(.) ='' and not(descendant::hr) and not(descendant::br) and not(descendant::img)]" priority="5"></xsl:template>
	<xsl:template match="abbr[@title]">
		<abbr>
			<xsl:copy-of select="@*[name() = 'title']"/>
			<xsl:apply-templates />
		</abbr>
	</xsl:template>
	
	<xsl:template match="acronym[normalize-space(.) ='' and not(descendant::hr) and not(descendant::br) and not(descendant::img)]" priority="5"></xsl:template>
	<xsl:template match="acronym[@title]">
		<acronym>
			<xsl:copy-of select="@*[name() = 'title']"/>
			<xsl:apply-templates />
		</acronym>
	</xsl:template>
	
	<xsl:template match="img">
		<img>
			<xsl:attribute name="style">
				<xsl:value-of select="php:function('website_XHTMLCleanerHelper::safeImgStyle', .)" />
			</xsl:attribute>
			<xsl:attribute name="src">
				<xsl:value-of select="php:function('website_XHTMLCleanerHelper::safeSrc', .)" />
			</xsl:attribute>
			<xsl:attribute name="class">
				<xsl:value-of select="php:function('website_XHTMLCleanerHelper::safeImgClass', .)" />
			</xsl:attribute> 
			<xsl:copy-of select="@*[name() = 'lang' or name() = 'alt' or name() = 'cmpref' or name() = 'usemediaalt' or name() = 'zoom' or name() = 'format' or name() = 'height' or name() = 'width']"/>
			<xsl:apply-templates />
		</img>
	</xsl:template>
	
	<xsl:template match="a[normalize-space(.) = '' and not(descendant::*)]" priority="5"></xsl:template>
	<xsl:template match="a[@name]" priority="6">
		<a class="anchor">
			<xsl:copy-of select="@*[name() = 'title' or name() = 'name']"/>
			<xsl:if test="not(@title)">
				<xsl:attribute name="title">
					<xsl:value-of select="@name"/>
				</xsl:attribute>
			</xsl:if>
			<xsl:apply-templates />
		</a>
	</xsl:template>
	
	<xsl:template match="a[not(@name)]">
		<a>
			<xsl:attribute name="class">
				<xsl:value-of select="php:function('website_XHTMLCleanerHelper::safeAClass', .)" />
			</xsl:attribute>
			<xsl:attribute name="href">
				<xsl:value-of select="php:function('website_XHTMLCleanerHelper::safeAHref', .)" />
			</xsl:attribute>			 
			<xsl:copy-of select="@*[name() = 'rel' or name() = 'lang' or name() = 'title' or name() = 'style']"/>
			<xsl:apply-templates />
		</a>
	</xsl:template>

	<!-- Support for japanase ruby text -->
	<xsl:template match="ruby[parent::ruby]"><xsl:apply-templates /></xsl:template>
	<xsl:template match="ruby[normalize-space(.) ='' and not(descendant::hr) and not(descendant::br) and not(descendant::img)]" priority="5"></xsl:template>
	<xsl:template match="ruby"><ruby><xsl:apply-templates /></ruby></xsl:template>	
	
	<xsl:template match="rb[parent::rb]"><xsl:apply-templates /></xsl:template>
	<xsl:template match="rb[normalize-space(.) ='' and not(descendant::hr) and not(descendant::br) and not(descendant::img)]" priority="5"></xsl:template>
	<xsl:template match="rb"><rb><xsl:apply-templates /></rb></xsl:template>
		
	<xsl:template match="rp[parent::rp]"><xsl:apply-templates /></xsl:template>
	<xsl:template match="rp[normalize-space(.) ='' and not(descendant::hr) and not(descendant::br) and not(descendant::img)]" priority="5"></xsl:template>
	<xsl:template match="rp"><rp><xsl:apply-templates /></rp></xsl:template>
	
	<xsl:template match="rt[parent::rt]"><xsl:apply-templates /></xsl:template>
	<xsl:template match="rt[normalize-space(.) ='' and not(descendant::hr) and not(descendant::br) and not(descendant::img)]" priority="5"></xsl:template>
	<xsl:template match="rt"><rt><xsl:apply-templates /></rt></xsl:template>		
</xsl:stylesheet>
