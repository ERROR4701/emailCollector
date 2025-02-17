<!DOCTYPE html>
<html lang="de">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student List</title>
</head>
<body>
    <h1>Student List</h1>

    <?php
    $uploadDir = 'secure/uploads/';

    // Prüfen, ob der Ordner existiert
    if (is_dir($uploadDir)) {
        // Alle Dateien im Ordner holen
        $files = glob($uploadDir . '*.csv');

        // Neuste Datei ermitteln
        $latestFile = null;
        if (!empty($files)) {
            usort($files, function($a, $b) {
                return filemtime($b) - filemtime($a);
            });
            $latestFile = $files[0];
        }

        if ($latestFile && file_exists($latestFile)) {
            echo "<p>Displaying File: <strong>" . basename($latestFile) . "</strong></p>";

            // Datei öffnen und Inhalte lesen
            if (($handle = fopen($latestFile, 'r')) !== false) {
                echo '<table border="1">';
                echo '<tr><th>ID</th><th>Student</br>First Name</th><th>Student</br>Last Name</th><th>Parents</br>Last Name</th><th>Parents</br>E-Mail</th><th>Action</th></tr>';

                $header = fgetcsv($handle); // Überspringen der Kopfzeile
                while (($data = fgetcsv($handle)) !== false) {
                    $id = isset($data[0]) ? htmlspecialchars($data[0]) : null;
                    $sfname = isset($data[1]) ? htmlspecialchars($data[1]) : null;
                    $slname = isset($data[2]) ? htmlspecialchars($data[2]) : null;
                    $plname = isset($data[3]) ? htmlspecialchars($data[3]) : null;
                    $email = isset($data[4]) ? htmlspecialchars($data[4]) : null;
                    include 'secure/config.php';
                    if($usedob == "Yes"){
                        $dob = isset($data[5]) ? htmlspecialchars($data[5]) : null;
                    }
                    
                    $missing_vars = [];
                    if (empty($id)) $missing_vars[] = "id";
                    if (empty($sfname)) $missing_vars[] = "student's first name";
                    if (empty($slname)) $missing_vars[] = "student's last name";
                    if (empty($plname)) $missing_vars[] = "parent's last name";
                    if (empty($email)) $missing_vars[] = "email";
                    if ($usedob == "Yes"){
                        if(empty($dob)) $missing_vars[] = "date of birth";
                    }

                    if (!empty($missing_vars)) {
                        die("<strong>Error: Data couldn't be retrieved from CSV-File, it is likely missing from the file: " . implode(", ", $missing_vars)."</strong>
                        </br>
                        <button class='sbutton'><a class='link' href='secure/dashboard.php'>Go to the Dashboard</a></button>");
                    }

                    // Überprüfen ob E-Mail bereits eingetragen ist
                    if($email != NULL){
                        $emailText = "E-Mail already submitted!";
                        $emailSubmitted = true;
                    }else{
                        $emailText = $email;
                        $emailSubmitted = false;
                    }
                    if (isset($_POST['check' . $id])) {
                        include 'secure/config.php';
                        if($usedob == 'Yes'){
                        echo '<script>
                                var input = prompt("Please enter your childs date of birth using the DD.MM.YYYY format.");
                                if (input == "' . $dob . '") {
                                    alert("' . $email . '");
                                }else{alert("Incorrect date of birth. Access denied.")}
                              </script>';
                        }else{
                            echo '<script>alert("'.$email.'");</script>';
                        }
                    }
                    echo '<tr>';
                    echo '<form method="post" action="action.php">';
                    echo '<td>' . $id . '</td>';
                    echo '<td>' . $sfname . '</td>';
                    echo '<td>' . $slname . '</td>';
                    echo '<td><input type="name" name="plname" value="' . $plname . '"</td>';
                    echo '<td><input type="email" name="email" value="' . $emailText . '" required></td>';
                    echo '<input type="hidden" name="sfname" value="' . $sfname . '">';
                    echo '<input type="hidden" name="slname" value="' . $slname . '">';
                    echo '<input type="hidden" name="file" value="' . $latestFile . '">';
                    echo '<input type="hidden" name="id" value="' . $id . '">';
                    echo '<td><button class="save" type="submit">Save</button></td>';
                    echo '</form>';
                    if($emailSubmitted){
                    echo '<form method="post">';
                    echo '<td><input class="save" type="submit" name="check'.$id.'" value="View E-Mail"/></td>';
                    echo '</form>';
                    }
                    echo '</tr>';
                }

                echo '</table>';
                fclose($handle);
            } else {
                echo "<p>Error: File couldn't be opened.</p>";
            }
        } else {
            echo '<p>No CSV-File in upload directory.</p>';
        }
    } else {
        echo "<p>Upload directory doesn't exist.</p>";
    }
    ?>
    <a href="secure/dashboard.php">Dashboard</a>
</body>
</html>
