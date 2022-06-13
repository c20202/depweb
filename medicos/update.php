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
$lastName = "";
$dui = "";
$phone = "";
$speciality = "";

//Errores
$nameError = $lastNameError = $duiError = $phoneError = $specialityError = "";

try {

    //Petición de actualizar médico
    if (isset($_POST["id"]) && !empty($_POST["id"])) {

        //Obtener id del medico
        $id = $_POST["id"];

        //Validar nombre
        $inputName = trim($_POST["name"]);
        if (empty($inputName)) {
            $nameError = "Nombre no debe estar en blanco.";
        } else {
            $name = $inputName;
        }

        //Validar apellido
        $inputLastName = trim($_POST["last_name"]);
        if (empty($inputLastName)) {
            $lastNameError = "Apellido no debe estar en blanco.";
        } else {
            $lastName = $inputLastName;
        }

        //Validar dui
        $inputDui = trim($_POST["dui"]);
        if (empty($inputDui)) {
            $duiError = "DUI no debe estar en blanco.";
        } elseif (strlen($inputDui) < 9) {
            $duiError = "El DUI debe contener 9 caracteres sin guión.";
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

        //Validar especialidad
        $inputSpeciality = trim($_POST["speciality"]);
        if (empty($inputSpeciality)) {
            $specialityError = "Especialidad no debe estar en blanco.";
        } else {
            $speciality = $inputSpeciality;
        }

        //Revisar si los campos van con errores
        if (empty($nameError) && empty($lastNameError) && empty($duiError) && empty($phoneError) && empty($specialityError)) {

            //Preparar consulta
            $sql = "UPDATE medicos SET nombre = ?, apellido = ?, dui = ?, telefono = ?, especialidad = ? WHERE id = ?";

            if ($stmt = mysqli_prepare($link, $sql)) {

                //Enlazar parámetros
                mysqli_stmt_bind_param($stmt, "sssssi", $paramName, $paramLastName, $paramDui, $paramPhone, $paramSpeciality, $paramId);

                //Asignar parámetros
                $paramName = $name;
                $paramLastName = $lastName;
                $paramDui = $dui;
                $paramPhone = $phone;
                $paramSpeciality = $speciality;
                $paramId = $id;

                //Ejecutar consulta
                if (mysqli_stmt_execute($stmt)) {
                    //Redirigir a índice de doctires
                    header("location: index.php");
                    exit();
                } else {
                    echo "Hubo un error al actualizar la información del médico.";
                }
            }
            mysqli_stmt_close($stmt);
        }

        mysqli_close($link);
    } else {
        if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {

            //Obtener id del medico
            $id = trim($_GET["id"]);

            //Consultar medico
            $sql = "SELECT * FROM medicos WHERE id = ?";
            if ($stmt = mysqli_prepare($link, $sql)) {
                //Enlazar parámetros
                mysqli_stmt_bind_param($stmt, "i", $paramId);

                $paramId = $id;
                if (mysqli_stmt_execute($stmt)) {
                    $result = mysqli_stmt_get_result($stmt);

                    if (mysqli_num_rows($result) == 1) {
                        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                        //Recuperar datos del medico
                        $name = $row["nombre"];
                        $lastName = $row["apellido"];
                        $dui = $row["dui"];
                        $phone = $row["telefono"];
                        $speciality = $row["especialidad"];

                    } else {
                        header("location: error.php");
                        exit();

                    }
                } else {
                    echo "Hubo un error al obtener la información del médico.";
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
    <title>Médicos</title>
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
                <h2 class="mt-5">Editar médico</h2>
                <p>Puede editar los campos para actualizar el registro de un médico.</p>
                <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" name="name"
                               class="form-control <?php echo (!empty($nameError)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $name; ?>">
                        <span class="invalid-feedback"><?php echo $nameError; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Apellido</label>
                        <input type="text" name="last_name"
                               class="form-control <?php echo (!empty($lastNameError)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $lastName; ?>">
                        <span class="invalid-feedback"><?php echo $lastNameError; ?></span>
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
                        <label>Especialidad</label>
                        <input type="text" name="speciality"
                               class="form-control <?php echo (!empty($specialityError)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $speciality; ?>">
                        <span class="invalid-feedback"><?php echo $specialityError; ?></span>
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
