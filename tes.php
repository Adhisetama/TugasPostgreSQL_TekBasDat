<?php

$host = '172.190.38.198';
$dbname = 'tbdproject';
$user = 'adhityabayu';
$password = 'basisdata123';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit();
}

function postgreQuery($queryString, $hasReturnValue=true) {
    global $pdo;
    try {
        $stmt = $pdo->prepare($queryString);
        $stmt->execute();
        return $hasReturnValue ? $stmt->fetchAll(PDO::FETCH_ASSOC) : true;
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}

?>