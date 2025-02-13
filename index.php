<!DOCTYPE html>
<html lang="de">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klassenliste</title>
</head>
<body>
    <h1>Klassenliste</h1>

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
            echo "<p>Angezeigte Datei: <strong>" . basename($latestFile) . "</strong></p>";

            // Datei öffnen und Inhalte lesen
            if (($handle = fopen($latestFile, 'r')) !== false) {
                echo '<table border="1">';
                echo '<tr><th>Lfd.</br>Nr.</th><th>Schüler</br>Vorname</th><th>Schüler</br>Nachname</th><th>Eltern</br>Nachname</th><th>Eltern</br>E-Mail</th><th>Aktion</th></tr>';

                $header = fgetcsv($handle); // Überspringen der Kopfzeile
                while (($data = fgetcsv($handle)) !== false) {
                    $id = htmlspecialchars($data[0]);
                    $svorname = htmlspecialchars($data[1]);
                    $snachname = htmlspecialchars($data[2]);
                    $enachname = htmlspecialchars($data[3]);
                    $email = htmlspecialchars($data[4]);
                    $dob = htmlspecialchars($data[5]);
                    
                    // Überprüfen ob E-Mail bereits eingetragen ist
                    if($email != NULL){
                        $emailText = "E-Mail bereits eingetragen!";
                        $emailSubmitted = true;
                    }else{
                        $emailText = $email;
                        $emailSubmitted = false;
                    }
                    if (isset($_POST['ueberpruefen' . $id])) {
                        echo '<script>
                                var eingabe = prompt("Bitte geben Sie das Geburtsdatum Ihres Kindes im Format TT.MM.JJJJ ein.");
                                if (eingabe == "' . $dob . '") {
                                    alert("' . $email . '");
                                }else{alert("Geburtsdatum stimmt nicht überein. Zugang verweigert.")}
                              </script>';
                    }
                    echo '<tr>';
                    echo '<form method="post" action="action.php">';
                    echo '<td>' . $id . '</td>';
                    echo '<td>' . $svorname . '</td>';
                    echo '<td>' . $snachname . '</td>';
                    echo '<td><input type="name" name="enachname" value="' . $enachname . '"</td>';
                    echo '<td><input type="email" name="email" value="' . $emailText . '" required></td>';
                    echo '<input type="hidden" name="svorname" value="' . $svorname . '">';
                    echo '<input type="hidden" name="snachname" value="' . $snachname . '">';
                    echo '<input type="hidden" name="file" value="' . $latestFile . '">';
                    echo '<td><button class="save" type="submit">Speichern</button></td>';
                    echo '</form>';
                    if($emailSubmitted){
                    echo '<form method="post">';
                    echo '<td><input class="save" type="submit" name="ueberpruefen'.$id.'" value="Überprüfen"/></td>';
                    echo '</form>';
                    }
                    echo '</tr>';
                }

                echo '</table>';
                fclose($handle);
            } else {
                echo '<p>Fehler: Datei konnte nicht geöffnet werden.</p>';
            }
        } else {
            echo '<p>Keine CSV-Dateien im Upload-Ordner gefunden.</p>';
        }
    } else {
        echo '<p>Upload-Ordner existiert nicht.</p>';
    }
    ?>
    <a href="secure/upload.php">Upload</a> <a href="secure/download.php">Download</a>
</body>
</html>
