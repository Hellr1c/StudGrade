<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "records";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sqlResetAutoIncrement = "ALTER TABLE students AUTO_INCREMENT = 1";
$conn->query($sqlResetAutoIncrement);


if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['manualSave'])) {
    $subjN = isset($_POST['subjN']) ? $_POST['subjN'] : '';
    $numbS = isset($_POST['numbS']) ? $_POST['numbS'] : '';
    $studPerSect = isset($_POST['studPerSect']) ? $_POST['studPerSect'] : '';

    for ($i = 1; $i <= $numbS; $i++) {
        for ($j = 1; $j <= $studPerSect; $j++) {
            $StuN = "Student " . $j;
            $StuS = $i;
            $StudSub = $subjN;
            $StudSessh = isset($_POST["session"]) ? $_POST["session"] : '';
            $StudGrade = isset($_POST["grade_{$i}_{$j}"]) ? $_POST["grade_{$i}_{$j}"] : '';

            
            $sql = "INSERT INTO students (StudNam, StudSect, StudSub, StudGrade, StudSess)
                    VALUES ('$StuN', '$StuS', '$StudSub', '$StudGrade', '$StudSessh')";

            if ($conn->query($sql) !== TRUE) {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
}

$sqlSelect = "SELECT * FROM students";
$result = $conn->query($sqlSelect);
?>

<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <div class="container">
        <form method="POST" id="Forming" action="Alvaro_Maki_DynamicExamScores.php">
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" name="subjN" id="subjN" placeholder="Subject Name">
                </div>
                <br>
                <div class="col-md-4">
                    <input type="number" name="numbS" id="numbS" placeholder="# of Sections">
                </div>
                <br>
                <div class="col-md-4">
                    <input type="number" name="studPerSect" id="studPerSect" placeholder="# of Students">
                </div>
            </div>
            <br>
            <button type="submit" class="btn btn-primary" id="generateButton">Generate</button>
        </form>
            <br>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Section</th>
                    <th>Subject</th>
                    <th>Grade</th>
                    <th>Session</th>
                </tr>
            </thead>
            <tbody>
                <?php
                
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$row['ID']}</td>";
                        echo "<td>{$row['StudNam']}</td>";
                        echo "<td>{$row['StudSect']}</td>";
                        echo "<td>{$row['StudSub']}</td>";
                        echo "<td class='editable' contenteditable='true' data-id='{$row['ID']}'>{$row['StudGrade']}</td>";
                        echo "<td>{$row['StudSess']}</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No data available</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <br>
        <button class="btn btn-success" id="btnSave">Save Info</button>
    </div>

    <script>
        document.querySelectorAll('.editable').forEach(function (cell) {
            cell.addEventListener('blur', function () {
                var id = cell.getAttribute('data-id');
                var newGrade = cell.innerText.trim();

                
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'updatechanges.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        console.log('Grade updated successfully');
                    }
                };
                xhr.send('id=' + id + '&newGrade=' + newGrade);
            });
        });

    
        document.getElementById('btnSave').addEventListener('click', function () {
            
            var manualSaveInput = document.createElement('input');
            manualSaveInput.type = 'hidden';
            manualSaveInput.name = 'manualSave';
            manualSaveInput.value = 'true';
            document.getElementById('Formiing').appendChild(manualSaveInput);

            
            document.getElementById('Forming').submit();
        });
    </script>
</body>

</html>
