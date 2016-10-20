	<html>

	<head>
		<title>Properties</title>

		<style>
		table, th, td {
			border: 1px solid black;
		}
		</style>

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
		
		<link href="./css/style.css" rel='stylesheet' type='text/css' />

	</head>

	<body>
		<br>
		<br>
		<h1 class="heading">Property List</h1>
		<div align="center">List of All Owned Property in the Database</div>
		<br>
		<?php
		// Connection information
		$dbhost = 'localhost';
		$dbuser = 'root';
		$dbpass = '';
		$conn = mysql_connect($dbhost, $dbuser, $dbpass);
		if(! $conn )
		{
			die('Could not connect: ' . mysql_error());
		}
		$sql = 'SELECT *
		FROM datapoint
		WHERE type="property"
		ORDER BY id';

		mysql_select_db('ics321');
		$retval = mysql_query( $sql, $conn );
		if(! $retval )
		{
			die('Could not get data: ' . mysql_error());
		}
		while($row = mysql_fetch_array($retval, MYSQL_ASSOC))
		{
			echo "ID :{$row['id']}  <br> ".
			"Name: {$row['name']} <br> ".
			"Address: {$row['address']} <br> ".
			"Latitude: {$row['lat']} <br> ".
			"Longitude: {$row['lng']} <br> ".
			"Type : {$row['type']} <br> ".
			"--------------------------------<br>";
		} 
		echo "Fetched data successfully\n";
		mysql_close($conn);
		?>

	</body>
	</html>
