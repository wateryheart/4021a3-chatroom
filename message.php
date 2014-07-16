<?php

// get the name from cookie
$name = "";
if (isset($_COOKIE["name"])) {
    $name = $_COOKIE["name"];
}

print "<?xml version=\"1.0\" encoding=\"utf-8\"?>";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Message Page</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
        <script language="javascript" type="text/javascript">
        //<![CDATA[
        var loadTimer = null;
        var request;
        var datasize;
        var lastMsgID;

        function load() {
            var username = document.getElementById("username");
            if (username.value == "") {
                loadTimer = setTimeout("load()", 100);
                return;
            }

            loadTimer = null;
            datasize = 0;
            lastMsgID = 0;

            var chatroom = document.getElementById("chatObj");
            if (typeof(chatroom.SetVariable)=='undefined') {
                chatroom = document.getElementById("flashObj");
            }
            chatroom.SetVariable("online", true);

            getUpdate();
        }

        function unload() {
            var username = document.getElementById("username");
            if (username.value != "") {
                request = new ActiveXObject("Microsoft.XMLHTTP");
                request.open("POST", "logout.php", true);
                request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                request.send(null);
                username.value = "";
            }
            if (loadTimer != null) {
                loadTimer = null;
                clearTimeout("load()", 100);
            }
        }

        function getUpdate() {
            request = new ActiveXObject("Microsoft.XMLHTTP");
            request.onreadystatechange = stateChange;
            request.open("POST", "server.php", true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send("datasize=" + datasize);
        }

        function stateChange() {
            if (request.readyState == 4 && request.status == 200 && request.responseText) {
                var xmlDoc;
                try {
                    xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
                    xmlDoc.loadXML(request.responseText);
                } catch (e) {
                    var parser = new DOMParser();
                    xmlDoc = parser.parseFromString(request.responseText, "text/xml");
                }
                datasize = request.responseText.length;
                updateChat(xmlDoc);
                getUpdate();
            }
        }

        function updateChat(xmlDoc) {
            /* Add your code here */
            var messages = xmlDoc.getElementsByTagName("message");  // point to the message nodes
            var msgStr = "";
            for (i = lastMsgID; i < messages.length; ++i) {
                // Obtain user name and message content from each message node, and add to the variable msg
                // We use "|" as a separator to separate each user name and message content
                var msg = messages.item(i);
                msgStr += "|" + msg.getAttribute("name");
                var file = msg.getAttribute("file");
                if (file == null) {
                    file = "";
                }
                msgStr += "|" + file;
                var linkifyMessage = linkify(msg.firstChild.nodeValue);
                msgStr += "|" +linkifyMessage;
 
            }
            msgStr += "|";

            lastMsgID = messages.length;

            var chatroom = document.getElementById("chatObj");
            if (typeof(chatroom.SetVariable)=='undefined') {
                chatroom = document.getElementById("flashObj");
            }

            // Pass the value to the variable newMessage of Flash
            chatroom.SetVariable("newMessage", msgStr);

        }

        function linkify(inputText) {
            var replacedText, replacePattern1, replacePattern2, replacePattern3;
            //URLs starting with http://, https://, or ftp://
            replacePattern1 = /(\b(https?|ftp):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gim;
            replacedText = inputText.replace(replacePattern1, '<u><a href="$1" target="_blank">$1</a></u>');
            //URLs starting with "www." (without // before it, or it'd re-link the ones done above).
            replacePattern2 = /(^|[^\/])(www\.[\S]+(\b|$))/gim;
            replacedText = replacedText.replace(replacePattern2, '$1<u><a href="http://$2" target="_blank">$2</a></u>');
            //Change email addresses to mailto:: links.
            replacePattern3 = /(([a-zA-Z0-9\-\_\.])+@[a-zA-Z\_]+?(\.[a-zA-Z]{2,6})+)/gim;
            replacedText = replacedText.replace(replacePattern3, '<u><a href="mailto:$1">$1</a></u>');
            return replacedText;
        }

        function openFile(file) {
            window.open(file);
        }
        //]]>
        </script>
    </head>
    <body style="text-align: left" onload="load()" onunload="unload()">
                <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase=
            "http://active.macromedia.com/flash2/cabs/swflash.cab#version=4,0,0,0" id=
            "chatObj" width="800" height="350">
            <param name="movie" value="chat.swf" />
            <param name="quality" value="high" />
            <param name="play" value="false" />
            <embed play="false" swliveconnect="true" id="flashObj" name="flashObj" src=
            "chat.swf" quality="high" width="800" height="350" type=
            "application/x-shockwave-flash" pluginspage=
            "http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" />
        </object>
        <object width="0" height="0"
            classid="CLSID:C348XXXX-A7F8-11D1-AA75-00C04FA34D72"
            codebase="#VERSION=2,0,0,0">
        </object>
        <form action="">
            <input type="hidden" value="<?php print $name; ?>" id="username" />
        </form>
    </body>
</html>
