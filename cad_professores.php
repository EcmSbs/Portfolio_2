<?php
// Variáveis para mensagem de feedback
$message = '';
$message_class = '';

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

// Verificar se o formulário foi submetido via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obter dados do formulário
    $nome_prof = $_POST['nome_prof'];
    $id_instituicao = $_POST['id_instituicao'];

    // Verificar se já existe um professor cadastrado para a mesma instituição
    $sql_check = "SELECT id FROM professores WHERE nome_prof = '$nome_prof' AND id_instituicao = $id_instituicao";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows > 0) {
        $message = 'Já existe um professor cadastrado com este nome para a mesma instituição.';
        $message_class = 'error';
    } else {
        // Preparar e executar a query de inserção
        $sql_insert = "INSERT INTO professores (nome_prof, id_instituicao) VALUES ('$nome_prof', $id_instituicao)";

        if ($conn->query($sql_insert) === TRUE) {
            $message = 'Professor cadastrado com sucesso!';
            $message_class = 'success';
        } else {
            $message = 'Erro ao cadastrar professor: ' . $conn->error;
            $message_class = 'error';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Professor</title>
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
        .btn-edit, .btn-delete {
            display: inline-block;
            padding: 8px 12px;
            text-align: center;
            text-decoration: none;
            background-color: #007bff;
            color: #fff;
            border-radius: 4px;
            margin-right: 5px;
        }
        .btn-edit:hover, .btn-delete:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Cadastro de Professor</h2>

        <!-- Mensagem de feedback -->
        <?php if (!empty($message)): ?>
            <div class="message <?php echo $message_class; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form action="cad_professores.php" method="post">
            <div class="form-group">
                <label for="nome_prof">Nome do Professor:</label>
                <input type="text" id="nome_prof" name="nome_prof" required>
            </div>
            <div class="form-group">
                <label for="id_instituicao">Instituição:</label>
                <select id="id_instituicao" name="id_instituicao" required>
                    <?php
                    // Consulta SQL para buscar as instituições
                    $sql = "SELECT id, nome_insti FROM instituicao";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['nome_insti']) . "</option>";
                        }
                    } else {
                        echo "<option value=''>Nenhuma instituição cadastrada</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <button type="submit">Cadastrar</button>
            </div>
        </form>

        <h3>Lista de Professores</h3>

        <!-- Formulário de filtro por instituição -->
        <form action="cad_professores.php" method="get">
            <div class="form-group">
                <label for="filtro_instituicao">Filtrar por Instituição:</label>
                <select id="filtro_instituicao" name="filtro_instituicao">
                    <option value="">Todas as instituições</option>
                    <?php
                    // Consulta SQL para buscar as instituições
                    $sql_inst = "SELECT id, nome_insti FROM instituicao";
                    $result_inst = $conn->query($sql_inst);

                    if ($result_inst->num_rows > 0) {
                        while($row_inst = $result_inst->fetch_assoc()) {
                            $selected = ($_GET['filtro_instituicao'] == $row_inst['id']) ? 'selected' : '';
                            echo "<option value='" . $row_inst['id'] . "' $selected>" . htmlspecialchars($row_inst['nome_insti']) . "</option>";
                        }
                    }
                    ?>
                </select>
                <button type="submit">Filtrar</button>
            </div>
        </form>

        <?php
        // Consulta SQL para buscar os professores e suas respectivas instituições
        $sql_professores = "SELECT p.id, p.nome_prof, i.nome_insti FROM professores p
                            LEFT JOIN instituicao i ON p.id_instituicao = i.id";

        // Aplicar filtro se uma instituição foi selecionada
        if (isset($_GET['filtro_instituicao']) && !empty($_GET['filtro_instituicao'])) {
            $filtro_instituicao = $_GET['filtro_instituicao'];
            $sql_professores .= " WHERE p.id_instituicao = $filtro_instituicao";
        }

        $result_professores = $conn->query($sql_professores);

        if ($result_professores->num_rows > 0) {
            echo "<table><tr><th>Nome do Professor</th><th>Instituição</th><th>Editar</th><th>Excluir</th></tr>";
            while($row = $result_professores->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['nome_prof']) . "</td>
                        <td>" . htmlspecialchars($row['nome_insti']) . "</td>
                        <td><a href='edit_professores.php?id=" . $row['id'] . "' class='btn-edit'>Editar</a></td>
                        <td><a href='delete_professor.php?id=" . $row['id'] . "' class='btn-delete'>Excluir</a></td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Nenhum professor cadastrado.</p>";
        }

        // Fechar conexão com o banco de dados
        $conn->close();
        ?>

        <a href="sistema.php" class="btn-return">Retornar para a Pagina Inicial do Sistema</a>
    </div>
</body>
</html>






