<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=Dias_descanso','root','alexisuco2026');
    echo "OK\n";
} catch (Exception $e) {
    echo "ERR: " . $e->getMessage() . "\n";
}
