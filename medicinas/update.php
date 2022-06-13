<?php
session_start();
require_once "../config.php";

//Check if user has permissions
if ($_SESSION["role_id"] != ROLE_ADMIN) {
    header("location: error.php?unauthorized=true");
    exit();
}

//Campos a llenar
$name = "";
$laboratory = "";
$description = "";
$quantity = 0;
$price = 0;

//Errores
$nameError = $laboratoryError = $descriptionError = $quantityError = $priceError = "";

try {

    //Petición de actualizar medicina
    if (isset($_POST["id"]) && !empty($_POST["id"])) {

        //Obtener id de la medicina
        $id = $_POST["id"];

        //Validar nombre
        $inputName = trim($_POST["name"]);
        if (empty($inputName)) {
            $nameError = "Nombre no debe estar en blanco.";
        } else {
            $name = $inputName;
        }

        //Validar laboratorio
        $inputLaboratory = trim($_POST["laboratory"]);
        if (empty($inputLaboratory)) {
            $laboratoryError = "Laboratorio no debe estar en blanco.";
        } else {
            $laboratory = $inputLaboratory;
        }

        //Validar descripción
        $inputDescription = trim($_POST["description"]);
        if (empty($inputDescription)) {
            $descriptionError = "Descripción no debe estar en blanco.";
        } else {
            $description = $inputDescription;
        }

        //Validar cantidad
        $inputQuantity = trim($_POST["quantity"]);
        if ($inputQuantity > 999) {
            $quantityError = "La cantidad máxima es 999.";
        } else if ($inputQuantity <= 0) {
            $quantityError = "La cantidad mínima es 1.";
        } else {
            $quantity = $inputQuantity;
        }

        //Validar precio
        $inputPrice = trim($_POST["price"]);
        if ($inputPrice <= 0) {
            $priceError = "El monto mínimo del precio debe ser $0.01";
        } else {
            $price = $inputPrice;
        }

        //Revisar si los campos van con errores
        if (empty($nameError) && empty($laboratoryError) && empty($descriptionError) && empty($quantityError) && empty($priceError)) {

            //Preparar consulta
            $sql = "UPDATE medicinas SET nombre = ?, laboratorio = ?, descripcion = ?, cantidad = ?, precio = ? WHERE id = ?";

            if ($stmt = mysqli_prepare($link, $sql)) {

                //Enlazar parámetros
                mysqli_stmt_bind_param($stmt, "sssssi", $paramName,$paramLaboratory, $paramDescription, $paramQuantity, $paramPrice, $paramId);

                //Asignar parámetros
                $paramName = $name;
                $paramLaboratory = $laboratory;
                $paramDescription = $description;
                $paramQuantity = $quantity;
                $paramPrice = $price;
                $paramId = $id;

                //Ejecutar consulta
                if (mysqli_stmt_execute($stmt)) {
                    //Redirigir a índice de farmacias
                    header("location: index.php");
                    exit();
                } else {
                    echo "Hubo un error al actualizar la información de la medicina.";
                }
            }
            mysqli_stmt_close($stmt);
        }

        mysqli_close($link);
    } else {
        if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {

            //Obtener id de la medicina
            $id = trim($_GET["id"]);

            //Consultar medicina
            $sql = "SELECT * FROM medicinas WHERE id = ?";
            if ($stmt = mysqli_prepare($link, $sql)) {
                //Enlazar parámetros
                mysqli_stmt_bind_param($stmt, "i", $paramId);

                $paramId = $id;
                if (mysqli_stmt_execute($stmt)) {
                    $result = mysqli_stmt_get_result($stmt);

                    if (mysqli_num_rows($result) == 1) {
                        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                        //Recuperar datos de la farmacia
                        $name = $row["nombre"];
                        $laboratory = $row["laboratorio"];
                        $description = $row["descripcion"];
                        $quantity = $row["cantidad"];
                        $price = $row["precio"];

                    } else {
                        header("location: error.php");
                        exit();

                    }
                } else {
                    echo "Hubo un error al obtener la información de la medicina.";
                }
            }

            mysqli_stmt_close($stmt);
            mysqli_close($link);

        } else {
            header("location: error.php");
            exit();
        }

    }

} catch (Exception $e) {
    echo $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Medicinas</title>
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
                <h2 class="mt-5">Editar medicina</h2>
                <p>Puede editar los campos para actualizar el registro de una medicina.</p>
                <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" name="name"
                               class="form-control <?php echo (!empty($nameError)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $name; ?>">
                        <span class="invalid-feedback"><?php echo $nameError; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Laboratorio</label>
                        <input type="text" name="laboratory" maxlength="50"
                               class="form-control <?php echo (!empty($laboratoryError)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $laboratory; ?>">
                        <span class="invalid-feedback"><?php echo $laboratoryError; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Descripción</label>
                        <input type="text" name="description" maxlength="100"
                               class="form-control <?php echo (!empty($descriptionError)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $description; ?>">
                        <span class="invalid-feedback"><?php echo $descriptionError; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Cantidad</label>
                        <input type="number" name="quantity"
                               class="form-control <?php echo (!empty($quantityError)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $quantity; ?>">
                        <span class="invalid-feedback"><?php echo $quantityError; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Precio</label>
                        <input type="number" step="0.01" name="price" placeholder="0.00"
                               class="form-control <?php echo (!empty($priceError)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $price; ?>">
                        <span class="invalid-feedback"><?php echo $priceError; ?></span>
                    </div>
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <input type="submit" class="btn btn-primary" value="Actualizar">
                    <a href="index.php" class="btn btn-secondary ml-2">Volver</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
