<?php
// Verificar se o parâmetro id foi passado
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Conectar ao banco de dados
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "jarvis";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Erro de conexão com o banco de dados: " . $conn->connect_error);
    }

    // Preparar e executar a query de exclusão
    $sql_delete = "DELETE FROM instituicao WHERE id = $id";

    if ($conn->query($sql_delete) === TRUE) {
        echo '<p>Instituição excluída com sucesso!</p>';
    } else {
        echo '<p>Erro ao excluir instituição: ' . $conn->error . '</p>';
    }

    $conn->close();
} else {
    echo '<p>Parâmetro ID não encontrado.</p>';
}
?>
