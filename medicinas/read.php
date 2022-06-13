<?php

require_once "../config.php";

//Petición de leer medicina
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {

    //Preparar consulta
    $sql = "SELECT * FROM medicinas WHERE id = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {

        //Enlace de parámetros
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        $param_id = trim($_GET["id"]);

        //Ejecutar consulta
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 1) {
                //Obtener la fila del registro encontrado
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                //Guardar propiedades de la medicina en variables
                $name = $row["nombre"];
                $laboratory = $row["laboratorio"];
                $description = $row["descripcion"];
                $quantity = $row["cantidad"];
                $price = $row["precio"];

            } else {
                //ID invalido, redirigir a pagina de error
                header("location: error.php");
                exit();
            }

        } else {
            echo "Hubo un error al obtener la información de la medicina.";
        }
    }

    //Cerrar conexión a bd
    mysqli_stmt_close($stmt);
    mysqli_close($link);

} else {
    header("location: error.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Medicinas</title>
    <style>
        .wrapper {
            width: 600px;
            margin: 0 auto;
        }
    </style>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
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
                <h1 class="mt-5 mb-3">Información de la medicina</h1>
                <div class="form-group">
                    <label>Nombre</label>
                    <p><b><?php echo $name?></b></p>
                </div>
                <div class="form-group">
                    <label>Laboratorio</label>
                    <p><b><?php echo $laboratory ?></b></p>
                </div>
                <div class="form-group">
                    <label>Descripción</label>
                    <p><b><?php echo $description ?></b></p>
                </div>
                <div class="form-group">
                    <label>Cantidad</label>
                    <p><b><?php echo $quantity ?></b></p>
                </div>
                <div class="form-group">
                    <label>Precio</label>
                    <p><b><?php echo "$" . $price ?></b></p>
                </div>
                <p><a href="index.php" class="btn btn-primary">Volver</a></p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
