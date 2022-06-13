<?php
session_start();
require_once "../config.php";

//Check if user has permissions
if ($_SESSION["role_id"] != ROLE_ADMIN) {
    header("location: error.php?unauthorized=true");
    exit();
}

//Campos a llenar
$names = "";
$lastNames = "";
$dui = "";
$phone = "";
$location = "";

//Errores
$namesError = $lastNamesError = $duiError = $phoneError = $locationError = "";

try {

    //Petición de actualizar paciente
    if (isset($_POST["id"]) && !empty($_POST["id"])) {

        //Obtener id del paciente
        $id = $_POST["id"];

        //Validar nombre
        $inputName = trim($_POST["names"]);
        if (empty($inputName)) {
            $namesError = "Nombres no debe estar en blanco.";
        } else {
            $names = $inputName;
        }

        //Validar apellidos
        $inputLastNames = trim($_POST["last_names"]);
        if (empty($inputLastNames)) {
            $lastNamesError = "Apellidos no debe estar en blanco.";
        } else {
            $lastNames = $inputLastNames;
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

        //Validar dui
        $inputDui = trim($_POST["dui"]);
        if (empty($inputDui)) {
            $duiError = "DUI no debe estar en blanco.";
        } elseif (strlen($inputDui) < 9) {
            $duiError = "DUI debe contener 9 caracteres.";
        } else {
            $dui = $inputDui;
        }

        //Validar ubicación
        $inputLocation = trim($_POST["location"]);
        if (empty($inputLocation)) {
            $locationError = "Ubicación no debe estar en blanco.";
        } else {
            $location = $inputLocation;
        }

        //Revisar si los campos van con errores
        if (empty($namesError) && empty($lastNamesError) && empty($phoneError) && empty($duiError) && empty($locationError)) {

            //Preparar consulta
            $sql = "UPDATE pacientes SET nombres = ?, apellidos = ?, dui = ?, telefono = ?, ubicacion = ? WHERE id = ?";

            if ($stmt = mysqli_prepare($link, $sql)) {

                //Enlazar parámetros
                mysqli_stmt_bind_param($stmt, "sssssi", $paramNames,$paramLastNames, $paramDui, $paramPhone, $paramLocation, $paramId);

                //Asignar parámetros
                $paramNames = $names;
                $paramLastNames = $lastNames;
                $paramDui = $dui;
                $paramPhone = $phone;
                $paramLocation = $location;
                $paramId = $id;

                //Ejecutar consulta
                if (mysqli_stmt_execute($stmt)) {
                    //Redirigir a índice de pacientes
                    header("location: index.php");
                    exit();
                } else {
                    echo "Hubo un error al actualizar la información del paciente.";
                }
            }
            mysqli_stmt_close($stmt);
        }

        mysqli_close($link);
    } else {
        if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {

            //Obtener id del paciente
            $id = trim($_GET["id"]);

            //Consultar paciente
            $sql = "SELECT * FROM pacientes WHERE id = ?";
            if ($stmt = mysqli_prepare($link, $sql)) {
                //Enlazar parámetros
                mysqli_stmt_bind_param($stmt, "i", $paramId);

                $paramId = $id;
                if (mysqli_stmt_execute($stmt)) {
                    $result = mysqli_stmt_get_result($stmt);

                    if (mysqli_num_rows($result) == 1) {
                        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                        //Recuperar datos del paciente
                        $names = $row["nombres"];
                        $lastNames = $row["apellidos"];
                        $dui = $row["dui"];
                        $phone = $row["telefono"];
                        $location = $row["ubicacion"];

                    } else {
                        header("location: error.php");
                        exit();

                    }
                } else {
                    echo "Hubo un error al obtener la información del paciente.";
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
    <title>Pacientes</title>
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
                <h2 class="mt-5">Editar paciente</h2>
                <p>Puede editar los campos para actualizar el registro de un paciente.</p>
                <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                    <div class="form-group">
                        <label>Nombres</label>
                        <input type="text" name="names"
                               class="form-control <?php echo (!empty($namesError)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $names; ?>">
                        <span class="invalid-feedback"><?php echo $namesError; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Apellidos</label>
                        <input type="text" name="last_names"
                               class="form-control <?php echo (!empty($lastNamesError)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $lastNames; ?>">
                        <span class="invalid-feedback"><?php echo $lastNamesError; ?></span>
                    </div>
                    <div class="form-group">
                        <label>DUI</label>
                        <input type="tel" name="dui" maxlength="9"
                               class="form-control <?php echo (!empty($duiError)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $dui; ?>">
                        <span class="invalid-feedback"><?php echo $duiError; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="tel" name="phone" maxlength="8"
                               class="form-control <?php echo (!empty($phoneError)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $phone; ?>">
                        <span class="invalid-feedback"><?php echo $phoneError; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Ubicación</label>
                        <input type="text" name="location"
                               class="form-control <?php echo (!empty($locationError)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $location; ?>">
                        <span class="invalid-feedback"><?php echo $locationError; ?></span>
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
