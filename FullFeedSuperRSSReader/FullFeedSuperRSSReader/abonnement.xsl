<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:msxsl="urn:schemas-microsoft-com:xslt" exclude-result-prefixes="msxsl"
>
    <xsl:output method="html" indent="yes"/>

  <xsl:template match="/">
    <xsl:apply-templates select="//outline"/>
  </xsl:template>
    <xsl:template match="outline">
      <p>
        <a>
          <xsl:attribute name="href">
            <xsl:text>http://ftr.fivefilters.org/makefulltextfeed.php?url=</xsl:text>
            <xsl:value-of select="@xmlUrl"/>
          </xsl:attribute>
          <xsl:attribute name="class">
            <xsl:text>feedTitle</xsl:text>
          </xsl:attribute>
          <xsl:value-of select="@title"/>
        </a>
      </p>
      
    </xsl:template>
</xsl:stylesheet>
