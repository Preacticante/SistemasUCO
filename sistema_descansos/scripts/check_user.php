<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=Dias_descanso','root','alexisuco2026');
    $stmt = $pdo->prepare("SELECT id, name, email FROM users WHERE email = :email LIMIT 1");
    $stmt->execute(['email' => 'test@example.com']);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        echo "FOUND: " . $row['id'] . " | " . $row['name'] . " | " . $row['email'] . "\n";
    } else {
        echo "NOT FOUND\n";
    }
} catch (Exception $e) {
    echo "ERR: " . $e->getMessage() . "\n";
}
