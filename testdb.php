<?php
require_once __DIR__ . '/config/database.php';

$conn = getDBConnection();
echo $conn ? "OK" : "FAIL";
