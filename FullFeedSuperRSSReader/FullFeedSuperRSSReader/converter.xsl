<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:msxsl="urn:schemas-microsoft-com:xslt" exclude-result-prefixes="msxsl"
>
    <xsl:output method="xml" indent="yes"/>
    <xsl:template match="/">
      <opml version="1.0">
        <head>
          <title>
            <xsl:value-of select="//title"/>
          </title>
        </head>
        <body>
          <xsl:apply-templates select="//outline"/>
        </body>
      </opml>
    </xsl:template>
  
    <xsl:template match="outline">
      <outline>
        <xsl:attribute name="text">
          <xsl:value-of select="@text"/>
        </xsl:attribute>
        <xsl:attribute name="title">
          <xsl:value-of select="@title"/>
        </xsl:attribute>
        <xsl:attribute name="type">
          <xsl:value-of select="@type"/>
        </xsl:attribute>
        <xsl:attribute name="xmlUrl">
            <xsl:text>http://montreal.dyndns-ip.com:8888/full-text/makefulltextfeed.php?url=</xsl:text><xsl:value-of select="@xmlUrl"/>
        </xsl:attribute>
        <xsl:attribute name="htmlUrl">
          <xsl:value-of select="@htmlUrl"/>
        </xsl:attribute>
      </outline>
    </xsl:template>
</xsl:stylesheet>
