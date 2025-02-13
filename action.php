<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">    
        <link rel="stylesheet" href="style.css">
    </head>
</html>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $latestFile = $_POST['file'] ?? null;
    $email = $_POST['email'] ?? null;
    $sfname = $_POST['sfname'] ?? null;
    $slname = $_POST['slname'] ?? null;
    $plname = $_POST['plname'] ?? null;
    $id = $_POST['id'] ?? null;

    if ($latestFile && $email && $sfname && $slname && $id && $plname) {
        if (file_exists($latestFile)) {
            $tempFile = $latestFile . '.tmp';

            if (($inputHandle = fopen($latestFile, 'r')) !== false && ($outputHandle = fopen($tempFile, 'w')) !== false) {
                $header = fgetcsv($inputHandle);
                if ($header) {
                    fputcsv($outputHandle, $header);
                }

                $updated = false;

                while (($row = fgetcsv($inputHandle)) !== false) {
                    if (isset($row[0],$row[1], $row[2]) && $row[0] === $id && $row[1] === $sfname && $row[2] === $slname) {
                        $row[3] = $plname; // Aktualisiere Eltern Nachname
                        $row[4] = $email; // Aktualisiere die E-Mail-Adresse
                        $updated = true;
                    }
                    fputcsv($outputHandle, $row);
                }

                fclose($inputHandle);
                fclose($outputHandle);

                if ($updated) {
                    rename($tempFile, $latestFile);
                    echo '<h1>The data was updated successfully.</h1>';
                } else {
                    unlink($tempFile);
                    echo '<h1>Error: No matching entry found.</h1>';
                }
            } else {
                echo "<h1>Error: File couldn't be opened.</h1>";
            }
        } else {
            echo "<h1>Error: File doesn't exist.</h1>";
        }
    } else {
        echo '<h1>Error: Invalid input.</h1>';
    }
} else {
    echo '<h1>Error: Invalid request method.</h1>';
}
?>
