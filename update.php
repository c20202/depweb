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
$address = "";
$phone = "";

//Errores
$nameError = $addressError = $phoneError = "";

try {

    //Petición de actualizar farmacia
    if (isset($_POST["id"]) && !empty($_POST["id"])) {

        //Obtener id de la farmacia
        $id = $_POST["id"];

        //Validar nombre
        $inputName = trim($_POST["name"]);
        if (empty($inputName)) {
            $nameError = "Nombre no debe estar en blanco.";
        } else {
            $name = $inputName;
        }

        //Validar dirección
        $inputAddress = trim($_POST["last_name"]);
        if (empty($inputAddress)) {
            $addressError = "Dirección no debe estar en blanco.";
        } else {
            $address = $inputAddress;
        }

        //Validar teléfono
        $inputPhone = trim($_POST["phone"]);
        if (empty($inputPhone)) {
            $phoneError = "Teléfono no debe estar en blanco.";
        } elseif (strlen($inputPhone) < 8) {
            $phoneError = "Teléfono debe contener 8 caracteres.";
        } else {
            $phone = $inputPhone;
        }

        //Revisar si los campos van con errores
        if (empty($nameError) && empty($addressError) && empty($phoneError)) {

            //Preparar consulta
            $sql = "UPDATE farmacias SET nombre = ?, direccion = ?, telefono = ? WHERE id = ?";

            if ($stmt = mysqli_prepare($link, $sql)) {

                //Enlazar parámetros
                mysqli_stmt_bind_param($stmt, "sssi", $paramName,$paramAddress, $paramPhone, $paramId);

                //Asignar parámetros
                $paramName = $name;
                $paramAddress = $address;
                $paramPhone = $phone;
                $paramId = $id;

                //Ejecutar consulta
                if (mysqli_stmt_execute($stmt)) {
                    //Redirigir a índice de farmacias
                    header("location: index.php");
                    exit();
                } else {
                    echo "Hubo un error al actualizar la información de la farmacia.";
                }
            }
            mysqli_stmt_close($stmt);
        }

        mysqli_close($link);
    } else {
        if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {

            //Obtener id de la farmacia
            $id = trim($_GET["id"]);

            //Consultar farmacia
            $sql = "SELECT * FROM farmacias WHERE id = ?";
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
                        $address = $row["direccion"];
                        $phone = $row["telefono"];

                    } else {
                        header("location: error.php");
                        exit();

                    }
                } else {
                    echo "Hubo un error al obtener la información de la farmacia.";
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
                <h2 class="mt-5">Editar farmacia</h2>
                <p>Puede editar los campos para actualizar el registro de una farmacia.</p>
                <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" name="name"
                               class="form-control <?php echo (!empty($nameError)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $name; ?>">
                        <span class="invalid-feedback"><?php echo $nameError; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Dirección</label>
                        <input type="text" name="last_name"
                               class="form-control <?php echo (!empty($addressError)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $address; ?>">
                        <span class="invalid-feedback"><?php echo $addressError; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="tel" name="phone" maxlength="8"
                               class="form-control <?php echo (!empty($phoneError)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $phone; ?>">
                        <span class="invalid-feedback"><?php echo $phoneError; ?></span>
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
