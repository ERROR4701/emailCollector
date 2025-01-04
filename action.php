<!DOCTYPE html>
    <html>
        <head>
            <link rel="stylesheet" href="style.css">
        </head>
    </html>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $latestFile = $_POST['file'] ?? null;
    $email = $_POST['email'] ?? null;
    $vorname = $_POST['vorname'] ?? null;
    $nachname = $_POST['nachname'] ?? null;

    if ($latestFile && $email && $vorname && $nachname) {
        if (file_exists($latestFile)) {
            $tempFile = $latestFile . '.tmp';

            if (($inputHandle = fopen($latestFile, 'r')) !== false && ($outputHandle = fopen($tempFile, 'w')) !== false) {
                $header = fgetcsv($inputHandle);
                if ($header) {
                    fputcsv($outputHandle, $header);
                }

                $updated = false;

                while (($row = fgetcsv($inputHandle)) !== false) {
                    if (isset($row[0], $row[1]) && $row[0] === $vorname && $row[1] === $nachname) {
                        $row[2] = $email; // Aktualisiere die E-Mail-Adresse
                        $updated = true;
                    }
                    fputcsv($outputHandle, $row);
                }

                fclose($inputHandle);
                fclose($outputHandle);

                if ($updated) {
                    rename($tempFile, $latestFile);
                    echo '<p>Die E-Mail-Adresse wurde erfolgreich aktualisiert.</p>';
                } else {
                    unlink($tempFile);
                    echo '<p>Fehler: Kein passender Eintrag gefunden.</p>';
                }
            } else {
                echo '<p>Fehler: Die Datei konnte nicht geöffnet werden.</p>';
            }
        } else {
            echo '<p>Fehler: Die angegebene Datei existiert nicht.</p>';
        }
    } else {
        echo '<p>Fehler: Ungültige Eingabedaten.</p>';
    }
} else {
    echo '<p>Fehler: Ungültige Anfragemethode.</p>';
}
?>
