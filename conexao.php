<?php


$servername = "localhost"; // Endereço do servidor MySQL (geralmente 'localhost' se estiver na mesma máquina)
$username = "root"; // Nome de usuário do MySQL
$password = ""; // Senha do MySQL
$database = "jarvis"; // Nome do banco de dados que você quer se conectar



// Conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $database);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}
?>
