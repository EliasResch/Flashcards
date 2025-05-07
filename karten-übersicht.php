<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartenübersicht</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Kartenübersicht</h1>
    <ul class="card-list">
        <?php
        // Beispiel-Daten: Ersetze dies durch eine Datenbankabfrage
        $karten = [
            ['id' => 1, 'name' => 'Karte 1'],
            ['id' => 2, 'name' => 'Karte 2'],
            ['id' => 3, 'name' => 'Karte 3'],
        ];

        foreach ($karten as $karte): ?>
            <li class="card-item">
                <span><?php echo htmlspecialchars($karte['name']); ?></span>
                <form method="POST" action="karte-loeschen.php" onsubmit="return confirm('Möchten Sie diese Karte wirklich löschen?');">
                    <input type="hidden" name="karte_id" value="<?php echo htmlspecialchars($karte['id']); ?>">
                    <button type="submit" class="delete-button">Löschen</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>