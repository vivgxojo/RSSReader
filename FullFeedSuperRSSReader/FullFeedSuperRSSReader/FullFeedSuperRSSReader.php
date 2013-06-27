<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link href="feed.css" rel="stylesheet" type="text/css" />
    <style>
        html, body {
            margin: 0;
            padding: 0;
            border: 0;
        }
        #output, #loading {
            position: absolute;
        }
        #output {
            top: 0px;
            width: 500px;
            bottom: 0px;
        }
        #loading {
            top: 0px;
            left: 500px;
            right: 0;
            bottom: 0px;
        }

    </style>
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

    <div id="output">
        <form method="get" action="FullFeedSuperRSSReader.php">
            <label for="Text1">Nom d'utilisateur </label><input id="Text1" type="text" /><br />
            <label for="Password1">Mot de passe </label><input id="Password1" type="password" /><br />
            <input id="Button1" type="submit" value="Ouvrir session" /><br />
        </form>
        Ou : <br />
        Importez votre fichier d'abonnements
        <input id="fileinput" type="file" /><br />
    </div>

    <div id="loading">
    </div>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" ></script>
<script type="text/javascript">
    //check for cookie
    var path = getCookie("path");
    if (path != null && path != "") {
        alert("Welcome again " + path);
    }

    function readSingleFile(evt) {
        
        //Retrieve the first (and only!) File from the FileList object
        var f = evt.target.files[0];

        //set a cookie
        setCookie("path", document.getElementById('fileinput').value, 365);

        if (f) {
            var r = new FileReader();
            r.onload = function (e) {
                var contents = e.target.result;

                //load le dom du fichier d'abonnements
                xmlDoc = $.parseXML(contents);

                //load le xslt
                xsl = loadXMLFile("abonnement.xsl");

                //transforme le fichier d'abonnements avec le xslt
                result = transform(xmlDoc, xsl);


                output = document.getElementById("output");
                output.appendChild(result);
                links = output.getElementsByTagName('a');

                //rss = loadXMLFile(links[0].href);
                //alert(rss); 

                //get rss complete feeds from all subscriptions
                var nbActiveRequest = 0;
                var content = '<?xml version="1.0" encoding="utf-8"?>\n<rss version="2.0">\n';
                $("#loading").text("Loading...");
                $(links).each(function () {
                    nbActiveRequest++;
                    $.get('http://w4.uqo.ca/monm30/echoXML.php?url=' + encodeURIComponent($(this).attr('href')), function (cont) {
                        $("#loading").text("Loading..." + nbActiveRequest);
                        nbActiveRequest--;
                        try {
				            rssFeed = $.parseXML(cont);
			            }
			            catch (e) {
				            console.log(e);
			            }
                        channel = rssFeed.getElementsByTagName("channel")[0];
                        //DEVRAIT LIMITER AUX ARTICLES RÉCENTS : UNE OU DEUX SEMAINE
                        //limite = loadXMLFile("limite.xsl");
                        //channel = transform(channel, limite);

                        //alert(xmlToString(channel));
                        content += xmlToString(channel);
                        if (nbActiveRequest == 0) {
                            $("#loading").text("done");
                            content += '\n</rss>'
                            //alert(content);
                            rss = $.parseXML(content);
                            rssXSL = loadXMLFile("feed.xsl");
                            rssResult = transform(rss, rssXSL);
                            $("#loading").html(rssResult);
                        }
                    });
                });

                //                var string = (new XMLSerializer()).serializeToString(result);
                //                
                //                var blob = new Blob(["<?xml version='1.0' encoding='UTF-8'?>\n"+string], {
                //                    type: "text/plain;charset=utf-8;",
                //                });
                //                saveAs(blob, "subscriptions.xml");
            }
            r.readAsText(f);
        } else {
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

//    //load le dom du fichier d'abonnements
//    xmlDoc = loadXMLFile("subscriptions.xml");

//    //load le xslt
//    xsl = loadXMLFile("abonnement.xsl");

//    //transforme le fichier d'abonnements avec le xslt
//    result = transform(xmlDoc, xsl);
    //    //alert(result);

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
