
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="style.css">

		<title>AQUAPONIC MONITORING SYSTEM</title>
	</head>

	<body>
		<center>HUMIDITY</center>
	
		<script src="script.js"></script>
		
		<button type="back">
		<a href="aquaponicmonitoringsystem.php" class="back-button">
        BACK
		</a>
      </button>

		<?php

		 
			$hostname = "localhost"; 
			$username = "root"; 
			$password = ""; 
			$database = "aquaponicmonitoringsystem_db"; 

			$conn = mysqli_connect($hostname, $username, $password, $database);

			if (!$conn) { 
				die("Connection failed: " . mysqli_connect_error()); 
			} 

			// Execute the SQL query
			$sql = "SELECT ID, datetime, humidity FROM dht11 ORDER BY datetime DESC LIMIT 100";
			$result = mysqli_query($conn, $sql);
		?>
		
		<table class="styled-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Date/Time</th>
                <th>Value</th>
            </tr>
        </thead>
        <tbody>
		<?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['ID']; ?></td>
                <td><?php echo $row['datetime']; ?></td>
                <td><?php echo $row['humidity']; ?></td>
            </tr>

			<?php } ?> 
        </tbody>
    </table>
	
<!--
		<table class="styled-table">
        <thead>
            <th>ID</th>
            <th>Date/Time</th>
            <th>Values</th>
        </thead>
		
       <?php while ($row = mysqli_fetch_assoc($result)) { ?>
			<tbody>
            <tr>
                <td><?php echo $row['ID']; ?></td>
                <td><?php echo $row['datetime']; ?></td>
                <td><?php echo $row['temperature']; ?></td>
            </tr>
        <?php } ?> 

		</tbody>
    </table>
-->
	</body>
</html>