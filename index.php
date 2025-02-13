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
                    $id = htmlspecialchars($data[0]);
                    $sfname = htmlspecialchars($data[1]);
                    $slname = htmlspecialchars($data[2]);
                    $plname = htmlspecialchars($data[3]);
                    $email = htmlspecialchars($data[4]);
                    $dob = htmlspecialchars($data[5]);
                    
                    // Überprüfen ob E-Mail bereits eingetragen ist
                    if($email != NULL){
                        $emailText = "E-Mail already submitted!";
                        $emailSubmitted = true;
                    }else{
                        $emailText = $email;
                        $emailSubmitted = false;
                    }
                    if (isset($_POST['check' . $id])) {
                        echo '<script>
                                var input = prompt("Please enter your childs date of birth using the DD.MM.YYYY format.");
                                if (eingabe == "' . $dob . '") {
                                    alert("' . $email . '");
                                }else{alert("Incorrect date of birth. Access denied.")}
                              </script>';
                    }
                    echo '<tr>';
                    echo '<form method="post" action="action.php">';
                    echo '<td>' . $id . '</td>';
                    echo '<td>' . $sfname . '</td>';
                    echo '<td>' . $slname . '</td>';
                    echo '<td><input type="name" name="enachname" value="' . $plname . '"</td>';
                    echo '<td><input type="email" name="email" value="' . $emailText . '" required></td>';
                    echo '<input type="hidden" name="svorname" value="' . $sfname . '">';
                    echo '<input type="hidden" name="snachname" value="' . $slname . '">';
                    echo '<input type="hidden" name="file" value="' . $latestFile . '">';
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
