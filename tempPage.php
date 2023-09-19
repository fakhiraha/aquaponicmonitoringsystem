<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>AQUAPONIC MONITORING SYSTEM</title>
</head>
<body>
	<button onclick="mainpage()">BACK</button>
    <center>TEMPERATURE</center>
    
	
    <!-- Dropdown to select the number of records to view -->
    <form id="record-limit-form" method="post">
        <label for="record-limit">Select the number of records to view:</label>
        <select id="record-limit" name="record_limit" onchange="changeRecordLimit()">
            <option value="15">15</option>
            <option value="30">30</option>
            <option value="all">All</option>
            <option value="today">Today</option>
        </select>
    </form>

    <script>
        function changeRecordLimit() {
            const recordLimit = document.getElementById("record-limit").value;
            document.getElementById("record-limit-form").submit();
        }
    </script>

    <script src="script.js"></script>
	
    <div class="table-container">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date/Time</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $hostname = "localhost";
                $username = "root";
                $password = "";
                $database = "aquaponicmonitoringsystem_db";

                $conn = mysqli_connect($hostname, $username, $password, $database);

                if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                // Get the selected record limit from the form data
                $recordLimit = isset($_POST['record_limit']) ? $_POST['record_limit'] : 15;

                // Check if the selected option is "Today"
                if ($recordLimit === 'today') {
                    $todayDate = date("Y-m-d"); // Get the current date in the format YYYY-MM-DD
                    $sql = "SELECT ID, datetime, temperature FROM temperature WHERE DATE(datetime) = '$todayDate' ORDER BY datetime DESC";
                } elseif ($recordLimit === 'all') {
                    $sql = "SELECT ID, datetime, temperature FROM temperature ORDER BY datetime DESC";
                } else {
                    $sql = "SELECT ID, datetime, temperature FROM temperature ORDER BY datetime DESC LIMIT $recordLimit";
                }

                // Execute the modified SQL query
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    foreach ($result as $row) {
                        ?>
                        <tr>
                            <td><?= $row['ID']; ?></td>
                            <td><?= $row['datetime']; ?></td>
                            <td><?= $row['temperature']; ?></td>
                        </tr>
                        <?php
                    }
                }
                mysqli_close($conn); // Close the database connection
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
