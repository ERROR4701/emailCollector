<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSV Upload</title>
</head>
<body>
    <h1>Upload CSV Datei</h1>

    <?php
    // Überprüfen, ob das Formular gesendet wurde
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Überprüfen, ob eine Datei hochgeladen wurde
        if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/'; // Zielordner

            // Zielordner erstellen, falls er nicht existiert
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $uploadedFile = $_FILES['csv_file']['tmp_name'];
            $fileName = basename($_FILES['csv_file']['name']);
            $targetPath = $uploadDir . $fileName;

            // Datei verschieben
            if (move_uploaded_file($uploadedFile, $targetPath)) {
                echo "<p>Datei wurde erfolgreich hochgeladen: <strong>$fileName</strong></p>";
            } else {
                echo "<p>Fehler: Datei konnte nicht gespeichert werden.</p>";
            }
        } else {
            echo "<p>Fehler: Keine Datei hochgeladen oder ein Fehler ist aufgetreten.</p>";
        }
    }
    ?>

    <form action="" method="post" enctype="multipart/form-data">
        <label for="csv_file">Wähle eine CSV-Datei aus:</label>
        <input type="file" name="csv_file" id="csv_file" accept=".csv" required>
        <br><br>
        <button type="submit">Hochladen</button>
    </form>
</body>
</html>
