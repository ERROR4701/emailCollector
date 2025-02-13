<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Dashboard</title>
</head>
<body>
    <h1>Upload CSV-File</h1>

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
                echo "<p>File was uploaded successfully: <strong>$fileName</strong></p>";
            } else {
                echo "<p>Error: File couldn't be saved.</p>";
            }
        }
    }
    ?>

    <form action="" method="post" enctype="multipart/form-data">
        <label for="csv_file">Wähle eine CSV-Datei aus:</label>
        <input type="file" name="csv_file" id="csv_file" accept=".csv" required>
        <br><br>
        <button class="sbutton" type="submit">Upload</button>
    </form>

    <h1>Download CSV-File</h1>

    <?php
    $uploadDir = 'uploads/';

    // Prüfen, ob der Ordner existiert
    if (is_dir($uploadDir)) {
        $files = array_diff(scandir($uploadDir), ['.', '..']); // Dateien im Ordner

        if (!empty($files)) {
            echo '<form method="post" action="">';
            echo '<label for="file">Select a file:</label>'; 
            echo '<select name="file" id="file">';

            foreach ($files as $file) {
                echo '<option value="' . htmlspecialchars($file) . '">' . htmlspecialchars($file) . '</option>';
            }

            echo '</select><br><br>';
            echo '<button class="sbutton" type="submit">Download</button>';
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
                    echo '<p>Error: File not found.</p>';
                }
            }
        } else {
            echo '<p>No files found.</p>';
        }
    } else {
        echo "<p>Upload directory doesn't exist.</p>";
    }
    ?>
    <?php
    if (isset($_POST['drop'])) {
        $usedob = $_POST['drop'];
        $var_str = var_export($usedob, true);
        $var = "<?php\n\n\$usedob = $var_str;\n\n?>";
        file_put_contents('config.php', $var);
    }
    ?>
    <h1>Use date of birth to protect E-Mail adresses?</h1>
    <form method="post">
        <select id="drop" name="drop">
            <?php
                include 'config.php';
                if($usedob == "No"){
                    echo '<option value="No">No</option>';
                    echo '<option value="Yes">Yes</option>';
                }else{
                    echo '<option value="Yes">Yes</option>';
                    echo '<option value="No">No</option>';
                }
            ?>
        </select>
        <input class="sbutton" type="submit" value="Save">
    </form>
    </br>
    </br>
    <button class="sbutton"><a style="text-decoration:none; color:black;" href="../index.php">Return to Start</a></button>
</body>
</html>
