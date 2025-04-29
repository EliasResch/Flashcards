<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET['deck'])) {
    $deck_name = preg_replace('/[^a-zA-Z0-9_]/', '', $_GET['deck']);
    $table_name = "deck_" . $deck_name;
    $conn = new mysqli("localhost", "root", "", "karteikarten");
    $original = $conn->real_escape_string($_POST['original']);
    $uebersetzung = $conn->real_escape_string($_POST['uebersetzung']);

    // Insert card into the specific deck table
    $sql = "INSERT INTO $table_name (original, uebersetzung) VALUES ('$original', '$uebersetzung')";

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
    <form method="POST" action="karten-erstellen.php?deck=<?php echo urlencode($_GET['deck']); ?>">
        <input type="text" name="original" class="styled-input" placeholder="Original" required><br>
        <input type="text" name="uebersetzung" class="styled-input" placeholder="Übersetzung" required><br>
        <input type="submit" class="cta-button" value="Zum Deck hinzufügen"><br>
        <input type="button" class="cta-button" value="Zur Übersicht" onclick="window.location.href='index.php';">
    </form>
</body>
</html>