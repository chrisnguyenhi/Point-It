<?php
// Database information (values hidden)
$db_username = 
$db_password = 
$db_name = 
$db_host = 

// Login mySQL with the database information
$mysqli = new mysqli($db_host, $db_username, $db_password, $db_name);

// If there is a connection error report it to the user
if (mysqli_connect_errno()) 
{	
	// Report to that the connection was unable to be made
	header('Unable to connect to the database'); 
	exit();
}

// Saving and deleting data points (database side)
if($_POST) 
{
	// Check to see if the request came from AJAX
	$xhr = $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'; 
	if (!$xhr){ 
		header('Request did not come from AJAX'); 
		exit();	
	}
	
	// Retrieve the data point's latitude and longitude
	$markedLatLang = explode(',',$_POST["latlang"]);
	// Save the latitude as a float
	$markedLat = filter_var($markedLatLang[0], FILTER_VALIDATE_FLOAT);
	// Save the longitutde as a float
	$markedLng = filter_var($markedLatLang[1], FILTER_VALIDATE_FLOAT);
	
	//Delete the data point
	if(isset($_POST["del"]) && $_POST["del"]==true)
	{
		// Run the query that deletes the data point based on its latitude and longtitude once the delection option is selected
		$results = $mysqli->query("DELETE FROM datapoint WHERE lat=$markedLat AND lng=$markedLng");
		// Report that the data point was deleted successfully
		if (!$results) {  
		  // Report that the data point was unable to be deleted
			header('Unable to delete data points'); 
			exit();
		} 
		// Else, the data point should be deleted successfully
		exit("Data point deleted successfully");
	}
	
	// Retrieve the data point's name, address/description, and type through the data point form
	// Take the form's name information and save it as a String
	$markedName = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
	// Take the form's address information and save it as a String
	$markedAddress = filter_var($_POST["address"], FILTER_SANITIZE_STRING);
	// Take the form's type information and save it as a String
	$markedType	= filter_var($_POST["type"], FILTER_SANITIZE_STRING);
	
	// Insert into the database based on the data point form information filled by the user
	$results = $mysqli->query("INSERT INTO datapoint (name, address, lat, lng, type) VALUES ('$markedName','$markedAddress',$markedLat, $markedLng, '$markedType')");
	if (!$results) {  
		  // If the point is unable to be saved, report the failure
		header('Unable to create and save data point'); 
		exit();
	} 
	
	// Create an output using the filled name and filled address/description field
	$output = '<h1 class="markers-heading">'.$markedName.'</h1><p>'.$markedAddress.'</p>';
	exit($output);
}

// Create a DOMDocument for the Map XML
$dom = new DOMDocument("1.0");
// Create the datapoint element node and append it
$node = $dom->createElement("datapoint"); 
$parnode = $dom->appendChild($node); 

// Select all the rows in the datapoint table
$results = $mysqli->query("SELECT * FROM datapoint WHERE 1");
if (!$results) {  
	// Report the inability to retrieve saved data points if the operation fails
	header('Unable to retrieve previous data points'); 
	exit();
} 

// Set the header's content type to text/xml
header("Content-type: text/xml"); 

// Add XML nodes for everything found in the database
while($obj = $results->fetch_object())
{
  // Create a DOM element for datapoint
	$node = $dom->createElement("datapoint");  
  // Make a new node and append the name, address, latitude, longitude, and type to it
	$newnode = $parnode->appendChild($node);  
  // In the new node, set name to name 
	$newnode->setAttribute("name",$obj->name);
  // In the new node, set address to address
	$newnode->setAttribute("address", $obj->address); 
  // In the new node, set latitude to latitude 
	$newnode->setAttribute("lat", $obj->lat); 
  // In the new node, set longitude to longitude 
	$newnode->setAttribute("lng", $obj->lng); 
  // In the new node, set type to type
	$newnode->setAttribute("type", $obj->type);	
}

// Save the external XML data and dump it into a tree as a String
echo $dom->saveXML();
