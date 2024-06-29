<?php
// Conectar ao banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$database = "jarvis";

$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexão
if ($conn->connect_error) {
    die("Erro de conexão com o banco de dados: " . $conn->connect_error);
}

// Verificar se o ID do professor foi fornecido via GET
if(isset($_GET['id'])) {
    $prof_id = $_GET['id'];

    // Consulta SQL para excluir o professor pelo ID
    $sql_delete = "DELETE FROM professores WHERE id = $prof_id";

    if ($conn->query($sql_delete) === TRUE) {
        echo "Professor excluído com sucesso.";
    } else {
        echo "Erro ao excluir professor: " . $conn->error;
    }
} else {
    echo "ID do professor não fornecido.";
}

// Fechar conexão ao final
$conn->close();
?>
