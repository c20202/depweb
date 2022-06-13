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

//Petición de crear un paciente
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //Validar nombre
    $inputNames = trim($_POST["names"]);
    if (empty($inputNames)) {
        $namesError = "Nombres no debe estar en blanco.";
    } else {
        $names = $inputNames;
    }

    //Validar apellidos
    $inputLastNames = trim($_POST["last_names"]);
    if (empty($inputLastNames)) {
        $lastNamesError = "Apellidos no debe estar en blanco.";
    } else {
        $lastNames = $inputLastNames;
    }

    //Validar dui
    $inputDui = trim($_POST["dui"]);
    if (empty($inputDui)) {
        $duiError = "Dui no debe estar en blanco.";
    } elseif (strlen($inputDui) < 9) {
        $duiError = "Dui debe contener 9 caracteres.";
    } else {
        $dui = $inputDui;
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

    //Validar ubicación
    $inputLocation = trim($_POST["location"]);
    if (empty($inputLocation)) {
        $locationError = "Ubicación no debe estar en blanco.";
    } else {
        $location = $inputLocation;
    }

    //Revisar si los campos van con errores
    if (empty($namesError) && empty($lastNamesError) && empty($duiError) && empty($phoneError) && empty($locationError)) {

        //Preparar consulta
        $sql = "INSERT INTO pacientes(nombres, apellidos, dui, telefono, ubicacion) VALUES (?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {

            //Enlazar parámetros
            mysqli_stmt_bind_param($stmt, "sssss", $paramNames, $paramLastNames, $paramDui, $paramPhone, $paramLocation);

            //Asignar parámetros
            $paramNames = $names;
            $paramLastNames = $lastNames;
            $paramDui = $dui;
            $paramPhone = $phone;
            $paramLocation = $location;

            //Ejecutar consulta
            if (mysqli_stmt_execute($stmt)) {
                //Redirigir a índice de pacientes
                header("location: index.php");
                exit();
            } else {
                echo "Hubo un error al ingresar el paciente.";
            }
        }
        mysqli_stmt_close($stmt);
    }

    mysqli_close($link);
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
                <h2 class="mt-5">Nuevo paciente</h2>
                <p>Llene los siguientes campos para ingresar un nuevo paciente.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
                        <label>Dui</label>
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
                    <input type="submit" class="btn btn-primary" value="Ingresar">
                    <a href="index.php" class="btn btn-secondary ml-2">Volver</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
