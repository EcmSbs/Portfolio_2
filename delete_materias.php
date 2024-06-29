<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "jarvis";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Erro de conexão com o banco de dados: ". $conn->connect_error);
    }

    $sql_delete = "DELETE FROM materias WHERE id = $id";

    if ($conn->query($sql_delete) === TRUE) {
        echo '<p>Matéria excluída com sucesso!</p>';
    } else {
        echo '<p>Erro ao excluir matéria: '. $conn->error. '</p>';
    }

    $conn->close();
} else {
    echo '<p>Parâmetro ID não encontrado.</p>';
}
?>