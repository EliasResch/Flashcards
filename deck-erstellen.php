<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $deck_name = $_POST['deck_name'];

    // Sanitize deck name
    $deck_name = preg_replace('/[^a-zA-Z0-9_]/', '', $deck_name);
    $table_name = "deck_" . $deck_name;

    // Create a new table for the deck
    $sql = "CREATE TABLE $table_name (
        id INT AUTO_INCREMENT PRIMARY KEY,
        original VARCHAR(255) NOT NULL,
        uebersetzung VARCHAR(255) NOT NULL
    )";
$conn = new mysqli("localhost", "root", "", "karteikarten");
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        echo "Fehler beim Erstellen des Decks: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Deck Erstellen</title>
</head>
<body>
    <form method="POST" action="deck-erstellen.php">
        <input type="text" name="deck_name" class="styled-input" placeholder="Name des Decks" required>
        <input type="submit" class="cta-button" value="Deck Erstellen">
    </form>
</body>
</html>