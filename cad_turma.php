<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Turmas</title>
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
        <h2>Cadastro de Turmas</h2>

        <!-- Mensagem de feedback -->
        <?php
        if (!empty($message)) {
            echo '<div class="message ' . $message_class . '">' . $message . '</div>';
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="id_instituicao">Instituição:</label>
                <select id="id_instituicao" name="id_instituicao" required>
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

                    // Query para obter instituições
                    $sql_inst = "SELECT id, nome_insti FROM instituicao";
                    $result_inst = $conn->query($sql_inst);

                    if ($result_inst->num_rows > 0) {
                        while($row_inst = $result_inst->fetch_assoc()) {
                            echo '<option value="' . $row_inst["id"] . '">' . htmlspecialchars($row_inst["nome_insti"]) . '</option>';
                        }
                    } else {
                        echo '<option value="">Nenhuma instituição cadastrada</option>';
                    }

                    $conn->close();
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="nome_turma">Nome da Turma:</label>
                <input type="text" id="nome_turma" name="nome_turma" required>
            </div>
            <div class="form-group">
                <label for="qtd_alunos">Quantidade de Alunos:</label>
                <input type="number" id="qtd_alunos" name="qtd_alunos">
            </div>
            <div class="form-group">
                <label for="numero_sala">Número da Sala:</label>
                <input type="text" id="numero_sala" name="numero_sala">
            </div>
            <div class="form-group">
                <button type="submit">Cadastrar</button>
            </div>
        </form>

        <h3>Lista de Turmas</h3>
        <?php
        // Conectar ao banco de dados
        $conn = new mysqli($servername, $username, $password, $database);

        if ($conn->connect_error) {
            die("Erro de conexão: " . $conn->connect_error);
        }

        // Query para obter turmas
        $sql_turmas = "SELECT t.id, t.nome_turma, t.qtd_alunos, t.numero_sala, i.nome_insti 
                       FROM turmas t
                       LEFT JOIN instituicao i ON t.id_instituicao = i.id";
        $result_turmas = $conn->query($sql_turmas);

        if ($result_turmas->num_rows > 0) {
            echo "<table><tr><th>Turma</th><th>Instituição</th><th>Qtd Alunos</th><th>Número Sala</th><th>Ações</th></tr>";
            while($row_turma = $result_turmas->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row_turma["nome_turma"]) . "</td>
                        <td>" . htmlspecialchars($row_turma["nome_insti"]) . "</td>
                        <td>" . htmlspecialchars($row_turma["qtd_alunos"]) . "</td>
                        <td>" . htmlspecialchars($row_turma["numero_sala"]) . "</td>
                        <td class='btn-actions'>
                            <a href='edit_turmas.php?id=" . $row_turma["id"] . "' class='btn-edit'>Editar</a>
                            <a href='delete_turmas.php?id=" . $row_turma["id"] . "' class='btn-delete'>Excluir</a>
                        </td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Nenhuma turma cadastrada.</p>";
        }

        $conn->close();
        ?>
        
        <a href="sistema.php" class="btn-return">Retornar para o Sistema</a>
    </div>
</body>
</html>

<?php
// Código PHP para cadastro de turmas
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectar ao banco de dados
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "jarvis";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Erro de conexão com o banco de dados: " . $conn->connect_error);
    }

    // Obter os dados do formulário
    $id_instituicao = $_POST['id_instituicao'];
    $nome_turma = $_POST['nome_turma'];
    $qtd_alunos = isset($_POST['qtd_alunos']) ? $_POST['qtd_alunos'] : null;
    $numero_sala = isset($_POST['numero_sala']) ? $_POST['numero_sala'] : null;

    // Verificar se já existe uma turma com o mesmo nome
    $sql_check = "SELECT * FROM turmas WHERE nome_turma = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param('s', $nome_turma);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $message = 'Erro: Já existe uma turma cadastrada com esse nome.';
        $message_class = 'error';
    } else {
        // Preparar e executar a query de inserção
        $sql = "INSERT INTO turmas (id_instituicao, nome_turma, qtd_alunos, numero_sala) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        if ($stmt === false) {
            die('Erro ao preparar a declaração: ' . $conn->error);
        }

        $stmt->bind_param('issi', $id_instituicao, $nome_turma, $qtd_alunos, $numero_sala);
        
        if ($stmt->execute()) {
            $message = 'Turma cadastrada com sucesso!';
            $message_class = 'success';
        } else {
            $message = 'Erro ao cadastrar turma: ' . $conn->error;
            $message_class = 'error';
        }

        $stmt->close();
    }

    $stmt_check->close();
    $conn->close();
}
?>
