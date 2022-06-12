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

//Petición de crear farmacia
if ($_SERVER["REQUEST_METHOD"] == "POST") {

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
        $sql = "INSERT INTO farmacias(nombre, direccion, telefono) VALUES (?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {

            //Enlazar parámetros
            mysqli_stmt_bind_param($stmt, "sss", $paramName, $paramAddress, $paramPhone);

            //Asignar parámetros
            $paramName = $name;
            $paramAddress = $address;
            $paramPhone = $phone;

            //Ejecutar consulta
            if (mysqli_stmt_execute($stmt)) {
                //Redirigir a índice de farmacias
                header("location: index.php");
                exit();
            } else {
                echo "Hubo un error al ingresar la farmacia.";
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
                <h2 class="mt-5">Nueva farmacia</h2>
                <p>Llene los siguientes campos para ingresar una nueva farmacia.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
                    <input type="submit" class="btn btn-primary" value="Ingresar">
                    <a href="index.php" class="btn btn-secondary ml-2">Volver</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>