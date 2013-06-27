<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link href="feed.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php

ini_set('display_errors', 1); 
ini_set('log_errors', 1); 
ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); 
error_reporting(E_ALL);

echo "Hello World!";
echo "php is working";
phpinfo();


    ?>

    <div id="sidebar">
        <div id="import">
			<form method="get" action="FullFeedSuperRSSReader.php">
				<label for="Text1">Nom d'utilisateur </label><input id="Text1" type="text" /><br />
				<label for="Password1">Mot de passe </label><input id="Password1" type="password" /><br />
				<input id="Button1" type="submit" value="Ouvrir session" /><br />
			</form>
			Ou : <br />
            Importez votre fichier d'abonnements
            <input id="fileinput" type="file" />
        </div>
    </div>

    <div id="mainContent">
    </div>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" ></script>
<script type="text/javascript">
    //check for cookie
    var path = getCookie("path");
    if (path != null && path != "") {
        alert("Welcome again " + path);
    }

    if (localStorage.getItem("abonnements")) {
        xmlDoc = $.parseXML(localStorage.getItem("abonnements"));
		loadOPMLDoc(xmlDoc);
    }

    function readSingleFile(evt) {
        //Retrieve the first (and only!) File from the FileList object
        var f = evt.target.files[0];

        if (f) {
            var r = new FileReader();
            r.onload = function (e) {
                var contents = e.target.result;

                //load le dom du fichier d'abonnements
                xmlDoc = $.parseXML(contents);
                localStorage.setItem("abonnements", new XMLSerializer().serializeToString(xmlDoc));
                loadOPMLDoc(xmlDoc);
            }
            r.readAsText(f);
        }
        else {
            alert("Failed to load file");
        }

    }
    document.getElementById('fileinput').addEventListener('change', readSingleFile, false);


    //retourne une string à partir dUn dom xml
    function xmlToString(xmlData) {

        var xmlString;
        //IE
        if (window.ActiveXObject) {
            xmlString = xmlData.xml;
        }
        // code for Mozilla, Firefox, Opera, etc.
        else {
            xmlString = (new XMLSerializer()).serializeToString(xmlData);
        }
        return xmlString;
    } 

    //retourne le dom d'un fichier xml
    function loadXMLFile(file) {
        if (window.ActiveXObject) {
            xhttp = new ActiveXObject("Msxml2.XMLHTTP.3.0");
        }
        else {
            xhttp = new XMLHttpRequest();
        }
        xhttp.open("GET", file, false);
        xhttp.send("");
        return xhttp.responseXML;
    }

    //retourne le résultat de la transformation xsl
    function transform(xml, xsl) {
        if (window.ActiveXObject) {
            ex = xml.transformNode(xsl);
            document.getElementById("output").innerHTML = ex;
        }
        // code for Mozilla, Firefox, Opera, etc.
        else if (document.implementation && document.implementation.createDocument) {
            xsltProcessor = new XSLTProcessor();
            xsltProcessor.importStylesheet(xsl);
            resultDocument = xsltProcessor.transformToFragment(xml, document);
            return resultDocument;
        }
    }

    function loadOPMLDoc(xmlDoc) {
        //load le xslt
        xsl = loadXMLFile("abonnement.xsl");

        //transforme le fichier d'abonnements avec le xslt
        result = transform(xmlDoc, xsl);

        output = document.getElementById("sidebar");
        output.appendChild(result);
        links = output.getElementsByTagName('a');
        $('.feedTitle').click(function(e) {
            $(".feedTitle").removeClass("feedSelected");
            $(this).addClass("feedSelected");
            loadFeedContent($(this).attr('href'));
            e.preventDefault();
        });

        //get rss complete feeds from all subscriptions
        var nbActiveRequest = 0;
        loadFeedContent($(links[0]).attr('href'));
    }

    function loadFeedContent(url, content) {
        $("#mainContent").text("Loading...");
        var content = '<?xml version="1.0" encoding="utf-8"?>\n<rss version="2.0">\n';

        $.get('http://w4.uqo.ca/monm30/echoXML.php?url=' + encodeURIComponent(url), function (cont) {
                try {
                    rssFeed = $.parseXML(cont);
                }
                catch (e) {
                    console.log(e);
                }
                channel = rssFeed.getElementsByTagName("channel")[0];
                content += xmlToString(channel);
                $("#mainContent").text("done");
                content += '\n</rss>'
                rss = $.parseXML(content);
                rssXSL = loadXMLFile("feed.xsl");
                rssResult = transform(rss, rssXSL);
                if(navigator.userAgent.indexOf("Firefox") > -1) {
                    $("#mainContent").html(rssResult);
                    $(".article").each(function() {
                        var contentDiv = this.firstChild.nextSibling;
                        contentDiv.innerHTML = contentDiv.textContent;
                    });
                }
                else {
                    $("#mainContent").html(rssResult);
                }
        });
    }

    function getCookie(c_name) {
        var c_value = document.cookie;
        var c_start = c_value.indexOf(" " + c_name + "=");
        if (c_start == -1) {
            c_start = c_value.indexOf(c_name + "=");
        }
        if (c_start == -1) {
            c_value = null;
        }
        else {
            c_start = c_value.indexOf("=", c_start) + 1;
            var c_end = c_value.indexOf(";", c_start);
            if (c_end == -1) {
                c_end = c_value.length;
            }
            c_value = unescape(c_value.substring(c_start, c_end));
        }
        return c_value;
    }

    function setCookie(c_name, value, exdays) {
        var exdate = new Date();
        exdate.setDate(exdate.getDate() + exdays);
        var c_value = escape(value) + ((exdays == null) ? "" : "; expires=" + exdate.toUTCString());
        document.cookie = c_name + "=" + c_value;
    }

    </script>
</body>
</html>
