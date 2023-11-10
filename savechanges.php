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
    $updatedData = json_decode(file_get_contents("php://input"), true);

    foreach ($updatedData as $data) {
        $id = $data['id'];
        $newGrade = $data['newGrade'];

        // Update grade in the database
        $sqlUpdateGrade = "UPDATE exam_scores SET grade = '$newGrade' WHERE id = $id";
        if ($conn->query($sqlUpdateGrade) !== TRUE) {
            echo "Error updating grade: " . $conn->error;
        }
    }
}

// Close the database connection
$conn->close();
?>
