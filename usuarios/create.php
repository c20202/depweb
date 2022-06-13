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
$username = "";
$roleId = "";

//Errores
$nameError = $usernameError = $roleIdError = "";

//Petición de crear farmacia
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //Validar nombre
    $inputName = trim($_POST["name"]);
    if (empty($inputName)) {
        $nameError = "Nombre no debe estar en blanco.";
    } else {
        $name = $inputName;
    }

    //Validar nombre de usuario
    $inputUsername = trim($_POST["username"]);
    if (empty($inputUsername)) {
        $usernameError = "Nombre de usuario no debe estar en blanco.";
    } else {
        $username = $inputUsername;
    }

    //Validar id rol
    $inputRoleId = trim($_POST["role_id"]);
    if (empty($inputRoleId)) {
        $roleIdError = "Debe asignar un rol.";
    } else {
        $roleId = $inputRoleId;
    }

    //Revisar si los campos van con errores
    if (empty($nameError) && empty($usernameError) && empty($roleIdError)) {

        //Preparar consulta
        $sql = "INSERT INTO usuarios(nombre, usuario, clave, rol_id) VALUES (?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($adminLink, $sql)) {

            //Enlazar parámetros
            mysqli_stmt_bind_param($stmt, "ssss", $paramName, $paramUsername, $paramPassword, $paramRoleId);

            //Asignar parámetros
            $paramName = $name;
            $paramUsername = $username;
            $paramPassword = bin2hex(openssl_random_pseudo_bytes(4));
            $paramRoleId = $roleId;

            //Ejecutar consulta
            if (mysqli_stmt_execute($stmt)) {
                //Redirigir a índice de farmacias
                header("location: index.php");
                exit();
            } else {
                echo "Hubo un error al ingresar el usuario.";
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
    <title>Usuarios</title>
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
                <h2 class="mt-5">Nuevo usuario</h2>
                <p>Llene los siguientes campos para ingresar un nuevo usuario.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" name="name"
                               class="form-control <?php echo (!empty($nameError)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $name; ?>">
                        <span class="invalid-feedback"><?php echo $nameError; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Usuario</label>
                        <input type="text" name="username"
                               class="form-control <?php echo (!empty($usernameError)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $username; ?>">
                        <span class="invalid-feedback"><?php echo $usernameError; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Rol</label>
                        <?php
                            $rolesSql = "SELECT * FROM roles";
                            if ($stmt = mysqli_prepare($adminLink, $rolesSql)) {
                                if (mysqli_stmt_execute($stmt)) {
                                    $result = mysqli_stmt_get_result($stmt);
                                    if (mysqli_num_rows($result) > 0) {
                                        echo '<select class="form-control" name="role_id">';
                                        echo "<option selected>Seleccione un rol</option>";
                                        while ($row = mysqli_fetch_array($result)) {
                                            echo "<option value='" . $row["id"] . "'>" . $row["nombre"] . "</option>";
                                        }
                                        echo '</select>';
                                    }
                                }
                            }
                        ?>
                        <span class='invalid-feedback'><?php $roleIdError ?></span>
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