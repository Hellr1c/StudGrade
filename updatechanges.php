<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "records";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $newGrade = $_POST['newGrade'];

    // Update grade in the database
    $sqlUpdateGrade = "UPDATE students SET StudGrade = '$newGrade' WHERE id = $id";
    if ($conn->query($sqlUpdateGrade) !== TRUE) {
        echo "Error updating grade: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
