<?php
mysql_connect("localhost", "root", "") or die("Error connecting to database: ".mysql_error());
    /*
        localhost - it's location of the mysql server, usually localhost
        root - your username
        third is your password
         
        if connection fails it will stop loading the page and display an error
    */

        mysql_select_db("ics321") or die(mysql_error());
        /* tutorial_search is the name of database we've created */
        ?>

        <!DOCTYPE HTML>
        <html>
        <head>
            <title>Search Results</title>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
            <link rel="stylesheet" type="text/css" href="./css/style.css"/>
            <body>
                <br><br>
                <h1 class="heading"> Search Results </h1>
                <form action="result.php" method="GET">
                    <input type="text" name="query" placeholder="Search Database..." />
                    <input type="submit" value="Search" />
                </form>
            </head>
            <?php
            $query = $_GET['query']; 
    // gets value sent over search form

            $min_length = 3;
    // you can set minimum length of the query if you want

    if(strlen($query) >= $min_length){ // if query length is more or equal minimum length then

        $query = htmlspecialchars($query); 
        // changes characters used in html to their equivalents, for example: < to &gt;

        $query = mysql_real_escape_string($query);
        // makes sure nobody uses SQL injection

        $raw_results = mysql_query("SELECT * FROM datapoint
            WHERE (`name` LIKE '%".$query."%') OR (`type` LIKE '%".$query."%') OR (`address` LIKE '%".$query."%')") or die(mysql_error());

        // * means that it selects all fields, you can also write: `id`, `title`, `text`
        // articles is the name of our table

        // '%$query%' is what we're looking for, % means anything, for example if $query is Hello
        // it will match "hello", "Hello man", "gogohello", if you want exact match use `title`='$query'
        // or if you want to match just full word so "gogohello" is out use '% $query %' ...OR ... '$query %' ... OR ... '% $query'

        if(mysql_num_rows($raw_results) > 0){ // if one or more rows are returned do following

            while($results = mysql_fetch_array($raw_results)){
            // $results = mysql_fetch_array($raw_results) puts data from database into array, while it's valid it does the loop

                echo "<p><h3>".$results['name']."</h3>"."Address/Description: ". $results['address']."</h3><br>"."Latitude: ". $results['lat']."</h3><br>"."Longitude: ". $results['lng']."</h3><br>"."Type: ". $results['type']."</p>";
                // posts results gotten from database(title and text) you can also show id ($results['id'])
            }

        }
        else{ // if there is no matching rows do following
            echo "No results";
        }

    }
    else{ // if query length is less than minimum
        echo "Minimum length is ".$min_length;
    }
    ?>
</body>
</html>