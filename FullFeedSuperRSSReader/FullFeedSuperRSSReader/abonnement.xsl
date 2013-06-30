<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:msxsl="urn:schemas-microsoft-com:xslt" exclude-result-prefixes="msxsl"
>
  <xsl:output method="html" indent="yes"/>

  <xsl:template match="/">
    <xsl:apply-templates select="//body"/>
  </xsl:template>

  <xsl:template match="body">
    <xsl:apply-templates select="outline"/>
  </xsl:template>

  <xsl:template match="outline">

    <xsl:choose>
      <xsl:when test="starts-with(@type, 'rss')">
        <span>
          <xsl:attribute name="class">
            <xsl:text>sideLine</xsl:text>
          </xsl:attribute>
          <a>
            <xsl:attribute name="class">
              <xsl:text>supp</xsl:text>
            </xsl:attribute>
            <xsl:attribute name="href">
              <xsl:text>#</xsl:text>
            </xsl:attribute>
            <xsl:text> x </xsl:text>
          </a>
          <a>
            <xsl:attribute name="href">
              <xsl:text>http://montreal.dyndns-ip.com:8888/full-text/makefulltextfeed.php?url=</xsl:text>
              <xsl:value-of select="@xmlUrl"/>
            </xsl:attribute>
            <xsl:attribute name="class">
              <xsl:text>feedTitle</xsl:text>
            </xsl:attribute>
            <xsl:value-of select="@title"/>
          </a>
        </span>
      </xsl:when>
      <xsl:otherwise>
        <!-- outline node is a folder -->
        <div class="feedFolder">
          <div class="folderTitle">
            <xsl:value-of select="@title"/>
          </div>
          <!-- apply template to child nodes -->
          <div class="folderContent">
            <xsl:apply-templates select="outline"/>
          </div>
        </div>
      </xsl:otherwise>
    </xsl:choose>
  </xsl:template>
</xsl:stylesheet>
