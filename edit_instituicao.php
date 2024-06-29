<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Instituição</title>
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
        <h2>Editar Instituição</h2>
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "jarvis";
        
        $conn = new mysqli($servername, $username, $password, $database);

        if ($conn->connect_error) {
            die("Erro de conexão: " . $conn->connect_error);
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && isset($_POST['nome_insti'])) {
            $id = $_POST['id'];
            $nome_insti = $_POST['nome_insti'];
            $sql = "UPDATE instituicao SET nome_insti='$nome_insti' WHERE id=$id";
            if ($conn->query($sql) === TRUE) {
                echo '<p style="color: green;">Instituição atualizada com sucesso!</p>';
            } else {
                echo '<p style="color: red;">Erro ao atualizar instituição: ' . $conn->error . '</p>';
            }
        } else if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "SELECT nome_insti FROM instituicao WHERE id=$id";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $nome_insti = $row['nome_insti'];
            } else {
                echo '<p style="color: red;">Instituição não encontrada.</p>';
                exit();
            }
        } else {
            echo '<p style="color: red;">ID da instituição não fornecido.</p>';
            exit();
        }

        $conn->close();
        ?>

        <form action="edit_instituicao.php" method="post">
            <div class="form-group">
                <label for="nome_insti">Nome da Instituição:</label>
                <input type="text" id="nome_insti" name="nome_insti" value="<?php echo htmlspecialchars($nome_insti); ?>" required>
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            </div>
            <div class="form-group">
                <button type="submit">Salvar Alterações</button>
                <a href="cad_instituicao.php" class="btn-back">Retornar</a>
            </div>
        </form>
    </div>
</body>
</html>
