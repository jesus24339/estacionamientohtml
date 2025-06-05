<?php
$host = 'localhost';
$db   = 'elsa';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    // Crear tablas si no existen
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS ingreso_usuario (
            id INT AUTO_INCREMENT PRIMARY KEY,
            tipo VARCHAR(50),
            nombre VARCHAR(100),
            apellido_paterno VARCHAR(100),
            apellido_materno VARCHAR(100),
            matricula VARCHAR(20),
            vehiculo VARCHAR(50),
            modelo VARCHAR(50),
            marca VARCHAR(50),
            color VARCHAR(50),
            anio VARCHAR(10),
            placa VARCHAR(20),
            hora_entrada DATETIME,
            hora_salida DATETIME
        )
    ");
    
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS ingreso_visitante (
            id INT AUTO_INCREMENT PRIMARY KEY,
            tipo VARCHAR(50),
            nombre VARCHAR(100),
            apellido_paterno VARCHAR(100),
            vehiculo VARCHAR(50),
            modelo VARCHAR(50),
            marca VARCHAR(50),
            color VARCHAR(50),
            anio VARCHAR(10),
            placa VARCHAR(20),
            hora_entrada DATETIME,
            hora_salida DATETIME
        )
    ");
    
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS pdf_registros (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre_archivo VARCHAR(255),
            fecha DATE,
            hora TIME,
            contenido LONGBLOB
        )
    ");
    
    // Tabla de usuarios (para búsqueda)
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS usuarios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            tipo VARCHAR(50),
            nombre VARCHAR(100),
            apellido_paterno VARCHAR(100),
            apellido_materno VARCHAR(100),
            matricula VARCHAR(20) UNIQUE,
            vehiculo VARCHAR(50),
            modelo VARCHAR(50),
            marca VARCHAR(50),
            color VARCHAR(50),
            anio VARCHAR(10),
            placa VARCHAR(20)
        )
    ");
    
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>