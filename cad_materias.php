<?php
// Incluir código para processar o formulário de cadastro
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar se as chaves existem no array $_POST
    if (isset($_POST['nome_materia'], $_POST['id_instituicao'])) {
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
        $nome_materia = $_POST['nome_materia'];
        $id_instituicao = $_POST['id_instituicao'];

        // Verificar se já existe uma matéria com o mesmo nome para a mesma instituição
        $sql_check = "SELECT * FROM materias WHERE nome_materia = ? AND id_instituicao = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param('si', $nome_materia, $id_instituicao);
        $stmt_check->execute();
        $stmt_check->store_result();

        $message = '';
        $message_class = '';

        if ($stmt_check->num_rows > 0) {
            $message = 'Erro: Já existe uma matéria cadastrada com esse nome para essa instituição.';
            $message_class = 'error';
        } else {
            // Preparar e executar a query de inserção
            $sql = "INSERT INTO materias (nome_materia, id_instituicao) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt === false) {
                die('Erro ao preparar a declaração: ' . $conn->error);
            }

            $stmt->bind_param('si', $nome_materia, $id_instituicao);

            if ($stmt->execute()) {
                $message = 'Matéria cadastrada com sucesso!';
                $message_class = 'success';
            } else {
                $message = 'Erro ao cadastrar matéria: ' . $conn->error;
                $message_class = 'error';
            }

            $stmt->close();
        }

        $stmt_check->close();
        $conn->close();
    } else {
        // Caso as chaves não existam no array $_POST
        $message = 'Erro: Campos do formulário não foram enviados corretamente.';
        $message_class = 'error';
    }
}

// Recuperar e exibir lista de matérias cadastradas
$materias = [];
$servername = "localhost";
$username = "root";
$password = "";
$database = "jarvis";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Query para obter as matérias, filtrando pela instituição selecionada
$sql = "SELECT m.id, m.nome_materia FROM materias m";

// Verificar se foi selecionada uma instituição para filtrar
if (isset($_GET['id_instituicao']) && !empty($_GET['id_instituicao'])) {
    $id_instituicao_filtro = $_GET['id_instituicao'];
    $sql .= " WHERE m.id_instituicao = $id_instituicao_filtro";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $materias[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Matéria</title>
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
        .form-group input, .form-group select {
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Cadastro de Matéria</h2>

        <!-- Mensagem de feedback -->
        <?php if (!empty($message)): ?>
            <div class="message <?php echo $message_class; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="nome_materia">Nome da Matéria:</label>
                <input type="text" id="nome_materia" name="nome_materia" required>
            </div>
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

                    // Query para obter as instituições
                    $sql = "SELECT id, nome_insti FROM instituicao";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['nome_insti']) . "</option>";
                        }
                    }

                    $conn->close();
                    ?>
                </select>
            </div>
            <div class="form-group">
                <button type="submit">Cadastrar</button>
            </div>
        </form>

        <a href="import_materias.php" class="btn-import">Importar Materias</a>

        <!-- Formulário de Filtragem -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
            <div class="form-group">
                <label for="filtro_instituicao">Filtrar por Instituição:</label>
                <select id="filtro_instituicao" name="id_instituicao">
                    <option value="">Todos</option>
                    <?php
                    // Conectar ao banco de dados
                    $conn = new mysqli($servername, $username, $password, $database);

                    if ($conn->connect_error) {
                        die("Erro de conexão: " . $conn->connect_error);
                    }

                    // Query para obter as instituições
                    $sql = "SELECT id, nome_insti FROM instituicao";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $selected = '';
                            if (isset($_GET['id_instituicao']) && $_GET['id_instituicao'] == $row['id']) {
                                $selected = 'selected';
                            }
                            echo "<option value='" . $row['id'] . "' $selected>" . htmlspecialchars($row['nome_insti']) . "</option>";
                        }
                    }

                    $conn->close();
                    ?>
                </select>
                <button type="submit">Filtrar</button>
            </div>
        </form>

        <?php if (!empty($materias)): ?>
            <table>
                <tr>
                    <th>Matéria</th>
                    <th>Ações</th>
                </tr>
                <?php foreach ($materias as $materia): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($materia['nome_materia']); ?></td>
                        <td class="btn-actions">
                            <a href="edit_materias.php?id=<?php echo $materia['id']; ?>" class="btn-edit">
                                Editar</a>
                            <a href="delete_materias.php?id=<?php echo $materia['id']; ?>" class="btn-delete">
                                Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>Nenhuma matéria cadastrada.</p>
        <?php endif; ?>

        <a href="sistema.php" class="btn-return">Retornar para o Sistema</a>
    </div>
</body>
</html>
