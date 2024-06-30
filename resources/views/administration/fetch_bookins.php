<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "Jorrit@99";
$dbname = "warehouseagenda";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, item_id, user_id, start_date, end_date FROM bookings";
$result = $conn->query($sql);

$bookings = array();

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
} else {
    echo "0 results";
}
$conn->close();

echo json_encode($bookings);
?>
