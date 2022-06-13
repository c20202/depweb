<?php
session_start();
require_once "../config.php";

//Check if user has permissions
if ($_SESSION["role_id"] != ROLE_ADMIN) {
    header("location: error.php?unauthorized=true");
    exit();
}

//Petición de eliminar farmacia
if (isset($_POST["id"]) && !empty($_POST["id"])) {

    //Preparar consulta
    $sql = "DELETE FROM farmacias WHERE id = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {

        //Enlazar parámetros
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        $param_id = trim($_POST["id"]);

        //Ejecutar consulta
        if (mysqli_stmt_execute($stmt)) {
            header("location: index.php");
            exit();
        } else {
            echo "Hubo un problema al eliminar la farmacia.";
        }

    }

    //Cerrar conexiones de la db
    mysqli_stmt_close($stmt);
    mysqli_close($link);

} else {
    //Si el ID no es válido
    if (empty(trim($_GET["id"]))) {
        header("location: error.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Farmacias</title>
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

<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" style="color:white"><i class="fa fa-hospital-o"></i> Clínica médica</a>
    </div>
</nav>

<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mt-5 mb-3">Eliminar farmacia</h2>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="alert alert-danger">
                        <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>"/>
                        <p>¿Estás seguro de que deseas eliminar este registro?</p>
                        <p>
                            <input type="submit" value="Eliminar" class="btn btn-danger">
                            <a href="index.php" class="btn btn-secondary">Cancelar</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>