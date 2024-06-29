<?php
// Verificar se o ID da turma foi fornecido via GET
if (isset($_GET['id'])) {
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

    // Preparar o ID da turma para exclusão
    $id_turma = $_GET['id'];

    // Query SQL para excluir a turma
    $sql = "DELETE FROM turmas WHERE id = $id_turma";

    // Executar a query e verificar se foi bem-sucedida
    if ($conn->query($sql) === TRUE) {
        echo "Turma deletada com sucesso.";
    } else {
        echo "Erro ao deletar turma: " . $conn->error;
    }

    // Fechar conexão com o banco de dados
    $conn->close();
} else {
    echo "ID da turma não especificado.";
}
?>
