<!DOCTYPE html>
<html>
<head>
	<title>Point It!</title>
	<button id="homeButton" class="bigbtn">Home</button>
	<button id="dbButton" class="bigbtn">View Database</button>
	<button id="allRButton" class="bigbtn">All Restaurants</button>
	<button id="allSButton" class="bigbtn">All Stores</button>
	<button id="allLButton" class="bigbtn">All Landmarks</button>
	<button id="allPButton" class="bigbtn">All Properties</button>
	<button id="allPAButton" class="bigbtn">All Public Areas</button>
	<button id="allDButton" class="bigbtn">All Danger Zones</button>
	<button id="allUButton" class="bigbtn">All Unknown Areas</button>
	<script type="text/javascript">
	
	document.getElementById("homeButton").onclick = function () {
		location.href = "./index.php";
	};
	document.getElementById("dbButton").onclick = function () {
		location.href = "./tabledata.php";
	};
	document.getElementById("allRButton").onclick = function () {
		location.href = "./allrestaurant.php";
	};
	document.getElementById("allSButton").onclick = function () {
		location.href = "./allstore.php";
	};
	document.getElementById("allLButton").onclick = function () {
		location.href = "./alllandmark.php";
	};
	document.getElementById("allPButton").onclick = function () {
		location.href = "./allproperty.php";
	};
	document.getElementById("allPAButton").onclick = function () {
		location.href = "./allpublicarea.php";
	};
	document.getElementById("allDButton").onclick = function () {
		location.href = "./alldangerous.php";
	};
	document.getElementById("allUButton").onclick = function () {
		location.href = "./allunknown.php";
	};
	</script>
	// API Key Hidden
	<script type="text/javascript" src="http://maps.googleapis.com/maps/api/MY-API-KEY"></script>
	<script type="text/javascript" src="./js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript">

	$(document).ready(function() {
		var map;

	//Startup the map
	mapStartup(); 
	
	//Function that sets up the Google Map on startup and it's configuration settings
	function mapStartup()
	{
		// The map options and settings for the Google Map when the users toggle, scroll, and zoom
		var mapOptions = 
		{ 
				// Center the map on start-up to Honolulu, Hawaii 
				center: {lat: 21.3069400, lng: -157.8583300}, 
				// Set the zoom sensitivity
				zoom: 10, 
				// Set the maximum zoom amount
				maxZoom: 20,
				// Min zoom at zero allows us to zoom to an "Earth View"
				minZoom: 0, 
				// Enable the users to make small zoom-ups
				zoomControlOptions: {
					style: google.maps.ZoomControlStyle.SMALL 
				},
				// Enable the users to scale the map
				scaleControl: true, 
				mapTypeId: google.maps.MapTypeId.ROADMAP 
			};

			// Set map to the Google Map
			map = new google.maps.Map(document.getElementById("google_map"), mapOptions);			
			
			//Load the saved information points from the XML data made in connection.php file
			$.get("connection.php", function (data) {
				// Find the data point and extract information from it
				$(data).find("datapoint").each(function () {
					// Get the point data's name
					var name = $(this).attr('name');
					// Get the address/description from the point data
					var address = '<p>'+ $(this).attr('address') +'</p>';
					// Get the type from the point data
					var type = $(this).attr('type');
					// Get the latitude and longitude from the point data (as floats)
					var point = new google.maps.LatLng(parseFloat($(this).attr('lat')),parseFloat($(this).attr('lng')));
					addPoint(point, name, address, false, false, false, "http://localhost/Point-It/img/saved.png");
				});
			});	
			
			// Allows the user to create a information point with a right-click on the Google Map
			google.maps.event.addListener(map, 'rightclick', function(event) {
				// Creates the form once a new information point is added onto the map
				var formData = '<p><div class="marker-edit">'+
				'<form action="ajax-save.php" method="POST" name="SaveMarker" id="SaveMarker">'+
				'<label for="pName"><span>Place Name :</span><input type="text" name="pName" class="save-name" placeholder="Enter Title" maxlength="40" /></label>'+
				'<label for="pDesc"><span>Description :</span><textarea name="pDesc" class="save-desc" placeholder="Enter Description or Address" maxlength="150"></textarea></label>'+
				'<label for="pType"><span>Type :</span> <select name="pType" class="save-type"><option value="restaurant">Restaurant</option><option value="store">Store</option><option value="landmark">Landmark</option><option value="property">Property</option><option value="publicarea">Public Area</option><option value="dangerous">Danger Zone</option>'+
				'<option value="unknown">Unknown</option></select></label>'+
				'</form>'+
				'</div></p><button name="save-marker" class="save-marker">Save Point</button>';

				// Adds a new point icon onto the Google Map where the user right-clicked
				addPoint(event.latLng, 'Data Point', formData, true, true, true, "http://localhost/Point-It/img/editing.png");
			});

}

	// Function to add a information point onto the map
	function addPoint(MapPos, MapTitle, MapDesc,  InfoOpenDefault, DragAble, Removable, iconPath)
	{	  	  		  
		
		//Creates a new data point using the Google Maps API's Marker
		var marker = new google.maps.Marker({
			position: MapPos,
			animation: google.maps.Animation.DROP,
			map: map,
			draggable:DragAble,
			title:"Point",
			icon: iconPath
		});
		
		//Sets the format for information in the pop-up form from the data point
		var content = $('<div class="marker-info-win">'+
			'<div class="marker-inner-win"><span class="info-content">'+
			'<h1 class="marker-heading">'+MapTitle+'</h1>'+
			MapDesc+ 
			'</span><button name="remove-marker" class="remove-marker" title="Remove Marker">Delete Point</button>'+
			'</div></div>');	

		
		//Create the window-shape for the form that appears with the data point
		var info = new google.maps.InfoWindow();
		info.setContent(content[0]);

		//Initialize the remove and save buttons on the data point form
		var removeButton = content.find('button.remove-marker')[0];
		var saveButton = content.find('button.save-marker')[0];

		//Allows the users to remove the data point once the remove button is clicked
		google.maps.event.addDomListener(removeButton, "click", function(event) {
			// Remove the data point
			removePoint(marker);
		});

		//If the save button is not there, the saving action will not work
		if(typeof saveButton !== 'undefined') 
		{
			//Ensure that the save button does something once it is clicked
			google.maps.event.addDomListener(saveButton, "click", function(event) {
				// Variable used to find and replace the content
				var mReplace = content.find('span.info-content'); 
				// The following are the values filled in the form of the data point (place name, description, and type)
				var mName = content.find('input.save-name')[0].value; 
				var mDesc = content.find('textarea.save-desc')[0].value; 
				var mType = content.find('select.save-type')[0].value; 
				
				// The name and description on the data point form must be filled out
				if(mName =='' || mDesc =='')
				{
					alert("Place name and place description required.");
				}else{
					// Saves the data point with the information filled in the form
					savePoint(marker, mName, mDesc, mType, mReplace); 
				}
			});
		}
		
		// Allow the Save button to do something when it is clicked		 
		google.maps.event.addListener(marker, 'click', function() {
				// When the data point is clicked open up the mini-window with the point information
				info.open(map,marker); 
			});

		// If the window is opened
		if(InfoOpenDefault) 
		{
			// When the data point is clicked open up the mini-window with the point information
			info.open(map,marker);
		}
	}
	
	// The function to remove a data point on the map
	function removePoint(Marker)
	{
		
		// Check if the data point is draggable. If the point is not draggable then it is already saved
		if(Marker.getDraggable()) 
		{
			// Remove the new data point. Only newly made data points are draggable
			Marker.setMap(null); 
		}
		else
		{
			// Remove the already saved data point from the database and map view 
			// Retrieve the position of the data point.
			var mLatLang = Marker.getPosition().toUrlValue(); 
			var myData = {del : 'true', latlang : mLatLang}; 
			// AJAX elements
			$.ajax({
				url: "connection.php",
				type: "POST",
				data: myData,
				// If the data is removed successfully then this function triggers
				success:function(data){
					// Set the data point you want to null (removal operation)
					Marker.setMap(null); 
					// Gives a pop-up alert to the user that the data was saved successfully
					alert(data);
				},
				// If there is an error in the remove operation, this function triggers
				error:function (xhr, ajaxOptions, thrownError){
					// Gives a pop-up alert to the user of the error that prevented the data from being saved
					alert(thrownError); 
				}
			});
		}

	}
	
	// Function used to save a data point
	function savePoint(Marker, mName, mAddress, mType, replaceWin)
	{
		// Save the newly created data point with the form information and point location
		var mLatLang = Marker.getPosition().toUrlValue(); 
		var myData = {name : mName, address : mAddress, latlang : mLatLang, type : mType }; 
		console.log(replaceWin);
		// AJAX Elements		
		$.ajax({
			type: "POST",
			url: "connection.php",
			data: myData,
			// If the data is saved successfully, this function triggers
			success:function(data){
				// Update the HTML page with the data saved
				replaceWin.html(data); 
				// Prevent the data point from being dragged again
				Marker.setDraggable(false); 
				// Change the data point color to show that it has been saved
				Marker.setIcon('http://localhost/Point-It/img/saved.png'); 
			},
			// If there is an error in saving, this function triggers
			error:function (xhr, ajaxOptions, thrownError){
				// Gives a pop-up alert to the user of the error that prevented the data from being saved
				alert(thrownError); 
			}
		});
	}
});
</script>

<?php
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_primaryDatabase = 'ics321';

// Connect to the database and make one if not exists
$dbConnection = new mysqli($db_host, $db_username, $db_password, $db_primaryDatabase);

// If there are errors (if the no# of errors is > 1), print out the error and cancel loading the page via exit();
if (mysqli_connect_errno()) {
	printf("Could not connect to MySQL databse: %s\n", mysqli_connect_error());
	exit();
}

// Basic Select Statement to check if the table exists
$queryt = "SELECT * FROM datapoint";
$result = mysqli_query($dbConnection, $queryt);
// If the Select Statement fails, make the datapoint table
if(empty($result)) {
	// Create Table Statement
	$queryt = "CREATE TABLE IF NOT EXISTS `datapoint` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`name` varchar(50) NOT NULL,
		`address` varchar(100) NOT NULL,
		`lat` float(10,6) NOT NULL,
		`lng` float(10,6) NOT NULL,
		`type` varchar(20) NOT NULL,
		PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
";
$result = mysqli_query($dbConnection, $queryt);
}
?>

<link href="./css/style.css" rel='stylesheet' type='text/css' />
</head>
<body>             
	<br>
	<br>
	<h1 class="heading">Point It!</h1>
	<div align="center"><headline>Point It! is a fun way to save and store location data. Data table created on start-up! Right Click to make a new Data Point</headline></div>
	<br>
	<form action="result.php" method="GET">
		<input type="text" name="query" placeholder="Search Database..."/>
		<input type="submit" value="Search" />
	</form>
	<br>
	<div id="google_map"></div>
</body>
<br>
<footer> Made by Christopher Nguyen</footer>
</html>
