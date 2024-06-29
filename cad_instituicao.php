<?php
// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // Obter os dados do formulário
    $nome_insti = $_POST['nome_insti'];

    // Verificar se já existe uma instituição com o mesmo nome
    $sql_check = "SELECT * FROM instituicao WHERE nome_insti = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param('s', $nome_insti);
    $stmt_check->execute();
    $stmt_check->store_result();

    $message = '';
    $message_class = '';

    if ($stmt_check->num_rows > 0) {
        $message = 'Erro: Já existe uma instituição cadastrada com esse nome.';
        $message_class = 'error';
    } else {
        // Preparar e executar a query de inserção
        $sql = "INSERT INTO instituicao (nome_insti) VALUES (?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die('Erro ao preparar a declaração: ' . $conn->error);
        }

        $stmt->bind_param('s', $nome_insti);

        if ($stmt->execute()) {
            $message = 'Instituição cadastrada com sucesso!';
            $message_class = 'success';
        } else {
            $message = 'Erro ao cadastrar instituição: ' . $conn->error;
            $message_class = 'error';
        }

        $stmt->close();
    }

    $stmt_check->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Instituição</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 20px;
        }
        .container {
            max-width: 800px;
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
        .btn-import {
            background-color: #ff9800;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
            display: block;
            text-align: center;
            text-decoration: none;
        }
        .btn-import:hover {
            background-color: #fb8c00;
        }
        .btn-return {
            background-color: #2196F3;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
            display: inline-block;
            text-decoration: none;
        }
        .btn-return:hover {
            background-color: #1e88e5;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f2f2f2;
        }
        .btn-actions {
            white-space: nowrap;
        }
        .btn-actions a {
            display: inline-block;
            margin-right: 5px;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 4px;
            text-align: center;
            font-size: 14px;
        }
        .btn-edit {
            background-color: #2196F3;
            color: white;
        }
        .btn-edit:hover {
            background-color: #1e88e5;
        }
        .btn-delete {
            background-color: #f44336;
            color: white;
        }
        .btn-delete:hover {
            background-color: #e53935;
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
        <h2>Cadastro de Instituição</h2>

        <!-- Mensagem de feedback -->
        <?php if (!empty($message)): ?>
            <div class="message <?php echo $message_class; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="nome_insti">Nome da Instituição:</label>
                <input type="text" id="nome_insti" name="nome_insti" required>
            </div>
            <div class="form-group">
                <button type="submit">Cadastrar</button>
            </div>
        </form>

        <a href="import_instituicoes.php" class="btn-import">Importar Instituições</a>

        <h3>Lista de Instituições</h3>
        <?php
        // Conectar ao banco de dados
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "jarvis";

        $conn = new mysqli($servername, $username, $password, $database);

        if ($conn->connect_error) {
            die("Erro de conexão: " . $conn->connect_error);
        }

        $sql = "SELECT id, nome_insti FROM instituicao";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table><tr><th>Instituição</th><th>Ações</th></tr>";
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row["nome_insti"]) . "</td>
                        <td class='btn-actions'>
                            <a href='edit_instituicao.php?id=" . $row["id"] . "' class='btn-edit'>Editar</a>
                            <a href='delete_instituicao.php?id=" . $row["id"] . "' class='btn-delete'>Excluir</a>
                        </td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Nenhuma instituição cadastrada.</p>";
        }

        $conn->close();
        ?>

        <a href="sistema.php" class="btn-return">Retornar para o Sistema</a>
    </div>
</body>
</html>
