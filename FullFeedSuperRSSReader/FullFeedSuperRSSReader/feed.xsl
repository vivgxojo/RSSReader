<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:content="http://purl.org/rss/1.0/modules/content/">
<xsl:output method="html" /> 
  
  <xsl:variable name="vrtfMonths">
    <m name="Jan" num="01"/>
    <m name="Feb" num="02"/>
    <m name="Mar" num="03"/>
    <m name="Apr" num="04"/>
    <m name="May" num="05"/>
    <m name="Jun" num="06"/>
    <m name="Jul" num="07"/>
    <m name="Aug" num="08"/>
    <m name="Sep" num="09"/>
    <m name="Oct" num="10"/>
    <m name="Nov" num="11"/>
    <m name="Dec" num="12"/>
  </xsl:variable>

  <xsl:variable name="vMonths" select=
   "document('')/*/xsl:variable
                   [@name='vrtfMonths']/*"
   />
  
<xsl:template match="/">
  
    <div id="content">
    
      <xsl:for-each select="rss/channel/item">
        
        <xsl:sort data-type="number" order="descending" select=
        "concat(substring(pubDate,13,4),
                $vMonths[@name 
                        = 
                         substring(current()/pubDate,9,3)]/@num,

                substring(pubDate,6,2),
                translate(substring(pubDate,18,8),
                          ':',
                          ''
                          )
                )
         "/>
      <div class="article">
		<h3>
			<a href="{link}" rel="bookmark"><xsl:value-of disable-output-escaping="yes" select="title"/></a>
			<xsl:text> </xsl:text>
			<span class="date"><xsl:value-of select="pubDate"/></span>
			(<a>
				<xsl:attribute name="href"> <xsl:value-of select="../link"/> </xsl:attribute>
				<xsl:value-of select="../title"/>
        <xsl:attribute name="class">
          <xsl:text>rssTitle</xsl:text>
        </xsl:attribute>
			</a>)
		</h3>
			<div>
			<xsl:choose>
				<xsl:when test="content:encoded"><xsl:value-of disable-output-escaping="yes" select="content:encoded" /></xsl:when>
				<xsl:when test="description"><xsl:value-of disable-output-escaping="yes" select="description" /></xsl:when>
			</xsl:choose>
			</div>
		      
      </div>
      </xsl:for-each>
     
    </div>
</xsl:template>
</xsl:stylesheet>
