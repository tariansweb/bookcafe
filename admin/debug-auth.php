<?php
require_once __DIR__ . '/../config/database.php';

$db = getDBConnection();

$stmt = $db->prepare("SELECT password FROM admin_users WHERE username='admin'");
$stmt->execute();
$row = $stmt->fetch();

echo "<h3>Stored hash:</h3>";
var_dump($row['password']);

echo "<h3>Testing password_verify:</h3>";
var_dump(password_verify("Admin123!", $row['password']));

echo "<h3>Testing known hash:</h3>";
$known = '$2y$10$GwUow6L3mqLQN6UmHlyoW.jr01TP4aMvHC86faAEqNjrjAfbT3/lm';
var_dump(password_verify("Admin123!", $known));
