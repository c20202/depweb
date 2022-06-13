<?php
require_once 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_destroy();
    header("location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cerrar sesión</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .wrapper {
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mt-5 mb-3 text-center">Cerrar sesión.</h2>
                <p class="text-center">¿Está seguro de que desea cerrar la sesión?</p>
                <br></br>
                <form method="post" class="text-center">
                    <input type="submit" class="btn btn-danger" value="Cerrar sesión">
                    <?php
                    if (isset($_GET["location"]) == "farmacias") {
                        echo '<a href="farmacias/index.php" class="btn btn-secondary ml-2">Volver</a>';
                    } else if (isset($_GET["location"]) == "medicinas") {
                        echo '<a href="medicinas/index.php" class="btn btn-secondary ml-2">Volver</a>';
                    } else if (isset($_GET["location"]) == "medicos") {
                        echo '<a href="medicos/index.php" class="btn btn-secondary ml-2">Volver</a>';
                    } else {
                        exit();
                    }
                    ?>
                </form>

            </div>
        </div>
    </div>
</div>
</body>
</html>