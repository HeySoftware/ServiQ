<?php
$link = mysql_connect('localhost', 'al342460', 'bdatos');
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
echo 'Connected successfully';
mysql_close($link);
?>

