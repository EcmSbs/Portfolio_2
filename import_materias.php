<?php

require 'endor/autoload.php';


use PhpOffice\PhpSpreadsheet\IOFactory;


$servername = "localhost";

$username = "root";

$password = "";

$database = "jarvis";


$conn = new mysqli($servername, $username, $password, $database);


if ($conn->connect_error) {

    die("Erro de conexão: ". $conn->connect_error);

}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {

    $fileName = $_FILES["file"]["tmp_name"];


    $spreadsheet = IOFactory::load($fileName);

    $sheet = $spreadsheet->getActiveSheet();

    $data = $sheet->toArray();


foreach ($data as $row) {

        if (!empty($row[0]) && !empty($row[1])) {

            $id_instituicao = $conn->real_escape_string($row[0]);

            $nome_materia = $conn->real_escape_string($row[1]);

            $sql = "INSERT INTO materias (id_instituicao, nome_materia) VALUES ('$id_instituicao', '$nome_materia')";

            if ($conn->query($sql) !== TRUE) {

                echo "Erro ao cadastrar matéria: ". $conn->error;

            }

        }

    }


    echo "Matérias importadas com sucesso!";

} else {

    echo "Nenhum arquivo foi enviado.";

}


$conn->close();


header("Location: index.php");

exit();

?>


<!DOCTYPE html>

<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <title>Importar Matérias</title>

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

        }

       .form-group button:hover {

            background-color: #45a049;

        }

    </style>

</head>

<body>

    <div class="container">

        <h2>Importar Matérias</h2>

        <form action="import_materias.php" method="post" enctype="multipart/form-data">

            <div class="form-group">

                <label for="file">Selecione o arquivo Excel:</label>

                <input type="file" id="file" name="file" accept=".xlsx, .xls" required>

            </div>

            <div class="form-group">

                <button type="submit">Importar</button>

            </div>

        </form>

    </div>

</body>

</html>