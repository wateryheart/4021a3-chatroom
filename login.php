<?php

// if name is not in the post data, exit
if (!isset($_POST["name"])) {
    header("Location: error.html");
    exit;
}

require_once('xmlHandler.php');

// create the chatroom xml file handler
$xmlh = new xmlHandler("chatroom.xml");
if (!$xmlh->fileExist()) {
    header("Location: error.html");
    exit;
}

$xmlh->openFile();

// get the 'users' element as the current element
$users_element = $xmlh->getElement("users");

// create a 'user' element for each user
$user_element = $xmlh->addElement($users_element, "user");

// add the name
$xmlh->setAttribute($user_element, "name", $_POST["name"]);

$xmlh->saveFile();

// set the name to the cookie
setcookie("name", $_POST["name"]);

// Cookie done, redirect to client.php (to avoid reloading of page from the client)
header("Location: client.php");

?>
