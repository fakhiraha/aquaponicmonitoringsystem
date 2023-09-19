<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="style.css">

		<title>AQUAPONIC MONITORING SYSTEM</title>
		<meta http-equiv="refresh" content="1">
	</head>

	<body>
		<center>AQUAPONIC MONITORING SYSTEM</center>
	
		<div class="time-date-container" id="current-time-date">
		</div>

		  <script src="script.js"></script>

		<?php

		 
			$hostname = "localhost"; 
			$username = "root"; 
			$password = ""; 
			$database = "aquaponicmonitoringsystem_db"; 

			$conn = mysqli_connect($hostname, $username, $password, $database);

			if (!$conn) { 
				die("Connection failed: " . mysqli_connect_error()); 
			} 

			//echo "Database connection is OK<br>"; 

			if(isset($_POST["temperature"])) {

				$t = $_POST["temperature"];

				$sql = "INSERT INTO temperature (temperature) VALUES (".$t.")"; 

				if (mysqli_query($conn, $sql)) { 
					echo "\nNew record created successfully"; 
				} else { 
					echo "Error: " . $sql . "<br>" . mysqli_error($conn); 
				}
			}
			
			if(isset($_POST["ph_act"])) {

				$ph_act = $_POST["ph_act"];

				$sql = "INSERT INTO ph (ph_act) VALUES (".$ph_act.")"; 

				if (mysqli_query($conn, $sql)) { 
					echo "\nNew record created successfully"; 
				} else { 
					echo "Error: " . $sql . "<br>" . mysqli_error($conn); 
				}
			}
			
			if(isset($_POST["distance"])) {

				$d = $_POST["distance"];

				$sql = "INSERT INTO waterlevel (distance) VALUES (".$d.")"; 

				if (mysqli_query($conn, $sql)) { 
					echo "\nNew record created successfully"; 
				} else { 
					echo "Error: " . $sql . "<br>" . mysqli_error($conn); 
				}
			}

			// Execute the SQL query
			$sqlt = "SELECT * FROM temperature ORDER BY id DESC LIMIT 1";
			$result = mysqli_query($conn, $sqlt);

			// Check if the query was successful
			if ($result) {
				// Fetch the row as an associative array
				$row = mysqli_fetch_assoc($result);

				// Access the data from the row
				$temperature = $row['temperature'];
			}
			
			$sqlph = "SELECT * FROM ph ORDER BY id DESC LIMIT 1";
			$result = mysqli_query($conn, $sqlph);

			// Check if the query was successful
			if ($result) {
				// Fetch the row as an associative array
				$row = mysqli_fetch_assoc($result);

				// Access the data from the row
				$ph_act = $row['ph_act'];
			}
			
			$sqld = "SELECT * FROM waterlevel ORDER BY id DESC LIMIT 1";
			$result = mysqli_query($conn, $sqld);

			// Check if the query was successful
			if ($result) {
				// Fetch the row as an associative array
				$row = mysqli_fetch_assoc($result);

				// Access the data from the row
				$distance = $row['distance'];
			}
		?>

		<div class="boxTEMP" onclick="tempPage()">
		
		<p>TEMPERATURE</p>
			<?php
			echo $temperature;
			?>
		</div>
		
		<div class="boxWL" onclick="waterlevelPage()">
	
		<p>WATER LEVEL</p>
			<?php
			echo $distance;
			?>
		</div>
		
		<div class="boxPH" onclick="phPage()">
		
		<p>PH VALUE</p>
			<?php
			echo $ph_act;
			?>
		</div>
	</body>
</html>