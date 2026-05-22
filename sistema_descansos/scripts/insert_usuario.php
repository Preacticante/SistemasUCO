<?php
try {
    $email = 'test@example.com';
    $name = 'Usuario de Prueba';
    $plain = 'secret123';

    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=Dias_descanso','root','alexisuco2026', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    $hash = password_hash($plain, PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("REPLACE INTO usuario (correo, nombre_completo, contrasena) VALUES (:correo, :nombre_completo, :contrasena)");
    $stmt->execute(['correo' => $email, 'nombre_completo' => $name, 'contrasena' => $hash]);

    echo "INSERTED: $email\n";

    $check = $pdo->prepare("SELECT contrasena FROM usuario WHERE correo = :correo LIMIT 1");
    $check->execute(['correo' => $email]);
    $row = $check->fetch(PDO::FETCH_ASSOC);
    if ($row && password_verify($plain, $row['contrasena'])) {
        echo "VERIFY OK\n";
    } else {
        echo "VERIFY FAIL\n";
    }
} catch (Exception $e) {
    echo 'ERR: ' . $e->getMessage() . "\n";
}
