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

// Variáveis para mensagem de feedback
$message = '';
$message_class = '';

// Verificar se o formulário foi submetido via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obter dados do formulário
    $id_professor = $_POST['id_professor'];
    $nome_prof = $_POST['nome_prof'];

    // Atualizar o nome do professor no banco de dados
    $sql_update = "UPDATE professores SET nome_prof = '$nome_prof' WHERE id = $id_professor";

    if ($conn->query($sql_update) === TRUE) {
        $message = 'Nome do professor atualizado com sucesso!';
        $message_class = 'success';
    } else {
        $message = 'Erro ao atualizar nome do professor: ' . $conn->error;
        $message_class = 'error';
    }
}

// Verificar se foi passado um ID válido via parâmetro GET
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_professor = $_GET['id'];

    // Consultar o professor com o ID especificado
    $sql_select = "SELECT id, nome_prof FROM professores WHERE id = $id_professor";
    $result = $conn->query($sql_select);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nome_prof = $row['nome_prof'];
    } else {
        // Se não encontrar o professor, exibir mensagem de erro
        $message = 'Professor não encontrado.';
        $message_class = 'error';
    }
} else {
    // Se não foi passado um ID válido, exibir mensagem de erro
    $message = 'ID de professor inválido.';
    $message_class = 'error';
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Professor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 20px;
        }
        .container {
            max-width: 600px;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: calc(100% - 20px);
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .form-group button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .form-group button:hover {
            background-color: #45a049;
        }
        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Editar Professor</h2>

        <!-- Mensagem de feedback -->
        <?php if (!empty($message)): ?>
            <div class="message <?php echo $message_class; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Formulário de edição do professor -->
        <?php if (isset($id_professor)): ?>
            <form action="edit_professores.php" method="post">
                <input type="hidden" name="id_professor" value="<?php echo $id_professor; ?>">
                <div class="form-group">
                    <label for="nome_prof">Nome do Professor:</label>
                    <input type="text" id="nome_prof" name="nome_prof" value="<?php echo htmlspecialchars($nome_prof); ?>" required>
                </div>
                <div class="form-group">
                    <button type="submit">Salvar Alterações</button>
                </div>
            </form>
        <?php endif; ?>

        <a href="cad_professores.php" class="btn-return">Voltar</a>
    </div>
</body>
</html>

<?php
// Fechar conexão ao final
$conn->close();
?>

