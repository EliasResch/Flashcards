<?php
// Datenbankverbindung einbinden
include 'index.php';

// Daten speichern, wenn das Formular abgeschickt wurde
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $original = $conn->real_escape_string($_POST['original']);
    $uebersetzung = $conn->real_escape_string($_POST['uebersetzung']);

    // SQL-Abfrage zum Einfügen der Daten
    $sql = "INSERT INTO karten (original, uebersetzung) VALUES ('$original', '$uebersetzung')";

    if ($conn->query($sql) === TRUE) {
        $message = "Karte erfolgreich hinzugefügt!";
    } else {
        $message = "Fehler: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Karten Erstellen</title>
</head>
<body class="karten-erstellen">
    <?php if (isset($message)): ?>
        <p class="message"><?php echo $message; ?></p>
    <?php endif; ?>
    <form method="POST" action="karten.sql">
        <input type="text" name="original" class="styled-input" placeholder="Original" required><br>
        <input type="text" name="uebersetzung" class="styled-input" placeholder="Übersetzung" required><br>
        <input type="submit" class="cta-button" value="Zum Deck hinzufügen"><br>
        <input type="button" class="cta-button" value="Zur Übersicht" onclick="window.location.href='index.php';">
    </form>
</body>
</html>