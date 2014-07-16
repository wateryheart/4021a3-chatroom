<?php

// Check validity of file upload
if (!is_uploaded_file($_FILES["voicefile"]["tmp_name"])) exit;

// Check the user name
if (!isset($_COOKIE["name"])) exit;

// Move the file to the voice directory
@mkdir($_COOKIE["name"], 0700);

// Generate a filename using the time value
$i = 0;
do {
    if ($i > 0) sleep(1);
    $filename = $_COOKIE["name"] . "/" . date("YmdHis") . ".wav";
    $i++;
} while ($i < 3 && file_exists($filename)); // try 3 times for unique
                                            // filename

// Save the file
if (file_exists($filename) ||
    !move_uploaded_file($_FILES['voicefile']['tmp_name'], $filename))
    exit;

// Return to the JavaScript
print $filename;

?>
