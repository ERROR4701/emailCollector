<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datei herunterladen</title>
</head>
<body>
    <h1>Datei herunterladen</h1>

    <?php
    $uploadDir = 'uploads/';

    // Prüfen, ob der Ordner existiert
    if (is_dir($uploadDir)) {
        $files = array_diff(scandir($uploadDir), ['.', '..']); // Dateien im Ordner

        if (!empty($files)) {
            echo '<form method="post" action="">';
            echo '<label for="file">Wähle eine Datei aus:</label>'; 
            echo '<select name="file" id="file">';

            foreach ($files as $file) {
                echo '<option value="' . htmlspecialchars($file) . '">' . htmlspecialchars($file) . '</option>';
            }

            echo '</select><br><br>';
            echo '<button type="submit">Herunterladen</button>';
            echo '</form>';

            // Überprüfen, ob eine Datei ausgewählt wurde
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['file'])) {
                $selectedFile = basename($_POST['file']); // Sicherheit: Nur den Dateinamen verwenden
                $filePath = $uploadDir . $selectedFile;

                if (file_exists($filePath)) {
                    // Herunterladen starten
                    ob_clean();
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($filePath));
                    readfile($filePath);
                    exit;
                } else {
                    echo '<p>Fehler: Datei nicht gefunden.</p>';
                }
            }
        } else {
            echo '<p>Keine Dateien im Ordner gefunden.</p>';
        }
    } else {
        echo '<p>Der Upload-Ordner existiert nicht.</p>';
    }
    ?>
</body>
</html>
