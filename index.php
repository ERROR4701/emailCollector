<!DOCTYPE html>
<html lang="en">
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
                echo '<tr><th>Vorname</th><th>Nachname</th><th>E-Mail</th><th>Aktion</th></tr>';

                $header = fgetcsv($handle); // Überspringen der Kopfzeile
                while (($data = fgetcsv($handle)) !== false) {
                    $vorname = htmlspecialchars($data[0]);
                    $nachname = htmlspecialchars($data[1]);
                    $email = htmlspecialchars($data[2]);
                    
                    if($email != NULL){
                        $email = "E-Mail bereits eingetragen!";
                    }

                    echo '<tr>';
                    echo '<form method="post" action="action.php">';
                    echo '<td>' . $vorname . '</td>';
                    echo '<td>' . $nachname . '</td>';
                    echo '<td><input type="email" name="email" value="' . $email . '" required></td>';
                    echo '<input type="hidden" name="vorname" value="' . $vorname . '">';
                    echo '<input type="hidden" name="nachname" value="' . $nachname . '">';
                    echo '<input type="hidden" name="file" value="' . $latestFile . '">';
                    echo '<td><button type="submit">Speichern</button></td>';
                    echo '</form>';
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
</body>
</html>
