<?php

require("phpsqlajax_dbinfo.php");

// Start XML file, create parent node

$dom = new DOMDocument("1.0");
$node = $dom->createElement("markers");
$parnode = $dom->appendChild($node);

// Opens a connection to a MySQL server

$connection=mysql_connect ('localhost', $username, $password);
if (!$connection) {  die('Not connected : ' . mysql_error());}

// Set the active MySQL database

$db_selected = mysql_select_db($database, $connection);
if (!$db_selected) {
  die ('Can\'t use db : ' . mysql_error());
}

// Select all the rows in the markers table

$query = "SELECT * FROM businesses WHERE 1";
$result = mysql_query($query);
if (!$result) {
  die('Invalid query: ' . mysql_error());
}

header("Content-type: text/xml");

// Iterate through the rows, adding XML nodes for each

while ($row = @mysql_fetch_assoc($result)){
  // ADD TO XML DOCUMENT NODE
  $node = $dom->createElement("businesses");
  $newnode = $parnode->appendChild($node);
		$newnode->setAttribute("name", $row['name']);
		$newnode->setAttribute("type", $row['type']);
		$newnode->setAttribute("phone", $row['phone']);
		$newnode->setAttribute("website", $row['website']);
		$newnode->setAttribute("latitude", $row['latitude']);
		$newnode->setAttribute("longitude", $row['longitude']);
}

echo $dom->saveXML();

?>