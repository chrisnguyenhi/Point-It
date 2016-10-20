	<html>

	<head>
		<title>Database Records</title>

		<style>
		table, th, td {
			border: 1px solid black;
		}
		</style>

		<button id="homeButton" class="bigbtn">Home</button>
		<button id="dbButton" class="bigbtn" >View Database</button>
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
		<br><br>
		<h1 class="heading">Database Records</h1>
		<div align="center">All Data Point Information Organized by ID</div>
		<br>
		<?php
		// Connection information
		$servername = "localhost";
		$username = "root";
		$password = "";
		$dbname = "ics321";

		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);
		
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 

		// Get data from the database and order it by data point id
		$sql = "SELECT * FROM datapoint ORDER BY id";
		$result = $conn->query($sql);

		// Create an HTML table from the data found in the database ordered by data point id
		if ($result->num_rows > 0) {
			echo "<table><tr><th>ID</th><th>Name</th><th>Details</th><th>Lat</th><th>Long</th><th>Type</th></tr>";
	    // Output the data
			while($row = $result->fetch_assoc()) {
				echo "<tr><td>".$row["id"]."</td><td>".$row["name"]."</td><td>".$row["address"]."</td><td>".$row["lat"]."</td><td>".$row["lng"]."</td><td>".$row["type"]."</td></tr>";
			}
			echo "</table>";
		} else {
			echo "0 results";
		}

		// Close the connection
		$conn->close();
		?>

	</body>
	</html>
