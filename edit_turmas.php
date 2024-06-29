<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Turma</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            max-width: 500px;
            width: 100%;
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
            width: 100%;
            padding: 10px;
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
            margin-right: 10px;
        }
        .form-group button:hover {
            background-color: #45a049;
        }
        .form-group .btn-back {
            background-color: #f44336;
        }
        .form-group .btn-back:hover {
            background-color: #e53935;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Editar Turma</h2>
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "jarvis";
        
        $conn = new mysqli($servername, $username, $password, $database);

        if ($conn->connect_error) {
            die("Erro de conexão: " . $conn->connect_error);
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && isset($_POST['nome_turma']) && isset($_POST['qtd_alunos']) && isset($_POST['numero_sala'])) {
            $id = $_POST['id'];
            $nome_turma = $_POST['nome_turma'];
            $qtd_alunos = $_POST['qtd_alunos'];
            $numero_sala = $_POST['numero_sala'];

            $sql = "UPDATE turmas SET nome_turma='$nome_turma', qtd_alunos='$qtd_alunos', numero_sala='$numero_sala' WHERE id=$id";
            if ($conn->query($sql) === TRUE) {
                echo '<p style="color: green;">Turma atualizada com sucesso!</p>';
            } else {
                echo '<p style="color: red;">Erro ao atualizar turma: ' . $conn->error . '</p>';
            }
        } else if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "SELECT nome_turma, qtd_alunos, numero_sala FROM turmas WHERE id=$id";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $nome_turma = $row['nome_turma'];
                $qtd_alunos = $row['qtd_alunos'];
                $numero_sala = $row['numero_sala'];
            } else {
                echo '<p style="color: red;">Turma não encontrada.</p>';
                exit();
            }
        } else {
            echo '<p style="color: red;">ID da turma não fornecido.</p>';
            exit();
        }

        $conn->close();
        ?>

        <form action="edit_turmas.php" method="post">
            <div class="form-group">
                <label for="nome_turma">Nome da Turma:</label>
                <input type="text" id="nome_turma" name="nome_turma" value="<?php echo htmlspecialchars($nome_turma); ?>" required>
            </div>
            <div class="form-group">
                <label for="qtd_alunos">Quantidade de Alunos:</label>
                <input type="number" id="qtd_alunos" name="qtd_alunos" value="<?php echo htmlspecialchars($qtd_alunos); ?>" required>
            </div>
            <div class="form-group">
                <label for="numero_sala">Número da Sala:</label>
                <input type="text" id="numero_sala" name="numero_sala" value="<?php echo htmlspecialchars($numero_sala); ?>">
            </div>
            <div class="form-group">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                <button type="submit">Salvar Alterações</button>
                <a href="cad_turma.php" class="btn-back">Retornar</a>
            </div>
        </form>
    </div>
</body>
</html>
