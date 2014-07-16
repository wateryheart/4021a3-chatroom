<?php

if (!isset($_COOKIE["name"])) {
    header("Location: error.html");
    return;
}

// get the name from cookie
$name = $_COOKIE["name"];

print "<?xml version=\"1.0\" encoding=\"utf-8\"?>";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Add Message Page</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
        <script type="text/javascript">
        //<![CDATA[
        function load() {
            var name = "<?php print $name; ?>";
            window.parent.frames["message"].document.getElementById("username").setAttribute("value", name)
            document.getElementById("username").setAttribute("value", name);
            setTimeout("document.getElementById('msg').focus()",100);
        }

        function uploadNanoGong() {
            // find the applet object
            var applet = document.getElementById("nanogong");
            var duration = applet.sendGongRequest("GetMediaDuration", "audio");
            console.log(duration);
            // get the length of the recorded audio
            if (duration <= 0) return true;

            // Tell the applet to post the voice recording to process_nanogong.php
            // A result will be returned and stored in the variable ret
            var ret = applet.sendGongRequest("PostToForm", "process_nanogong.php",
                                             "voicefile", "", "temp");
            console.log("ret: "+ret);
            // if the value of variable ret is null or empty, the voice upload has failed
            if (ret == null || ret == "") {
                alert("Failed to submit the voice recording!");
                return false;
            }

            // set the filename form field
            document.getElementById("filename").value = ret;
            // document.getElementById("filename").setAttribute("value", ret);
            console.log(document.getElementById("filename").value);
            return true;
        }
        //]]>
        </script>
    </head>
    <body style="text-align: left" onload="load()">
        <form action="add_message.php" method="post" onsubmit="return uploadNanoGong();">
            <table border="0" cellspacing="5" cellpadding="0">
                <tr>
                    <td>What is your message?</td>
                </tr>
                <tr>
                    <td><input class="text" type="text" name="message" id="msg" style= "width: 600px" /></td>
                    <td>
                        <applet id="nanogong" archive="nanogong.jar" code="gong.NanoGong" width="180" height="40">
                            <param name="AudioFormat" value="ImaADPCM" />
                        </applet>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="hidden" name="username" id="username" value = "<?php $name; ?>" />
                        <input type="hidden" name="filename" id="filename" value = "" />
                        <input class="button" type="submit" value="Send Your Message" style="width: 200px" />
                    </td>
                </tr>
            </table>
        </form>
        <hr />
        <form action="logout.php" method="post" onsubmit="alert('Goodbye!')">
            <table border="0" cellspacing="5" cellpadding="0">
                <tr style="border-top: 1px solid gray">
                    <td><input class="button" type="submit" value="Logout" style="width: 200px" /></td>
                </tr>
            </table>
        </form>
    </body>
</html>
