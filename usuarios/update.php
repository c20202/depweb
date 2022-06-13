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
$role_id = "";

//Errores
$nameError = $roleIdError = "";

try {

    //Petición de actualizar usuario
    if (isset($_POST["id"]) && !empty($_POST["id"])) {

        //Obtener id del usuario
        $id = $_POST["id"];

        //Validar nombre
        $inputName = trim($_POST["name"]);
        if (empty($inputName)) {
            $nameError = "Nombre no debe estar en blanco.";
        } else {
            $name = $inputName;
        }

        //Validar rol
        $inputRoleId = trim($_POST["role_id"]);
        if (empty($inputRoleId)) {
            $roleIdError = "Debe asignarse un rol.";
        } else {
            $role_id = $inputRoleId;
        }

        //Revisar si los campos van con errores
        if (empty($nameError) && empty($roleIdError)) {

            //Preparar consulta
            $sql = "UPDATE usuarios SET nombre = ?, rol_id = ? WHERE id = ?";

            if ($stmt = mysqli_prepare($adminLink, $sql)) {

                //Enlazar parámetros
                mysqli_stmt_bind_param($stmt, "ssi", $paramName,$paramRoleId, $paramId);

                //Asignar parámetros
                $paramName = $name;
                $paramRoleId = $role_id;
                $paramId = $id;

                //Ejecutar consulta
                if (mysqli_stmt_execute($stmt)) {
                    //Redirigir a índice de usuarios
                    header("location: index.php");
                    exit();
                } else {
                    echo "Hubo un error al actualizar la información del usuario.";
                }
            }
            mysqli_stmt_close($stmt);
        }

        mysqli_close($link);
    } else {
        if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {

            //Obtener id del usuario
            $id = trim($_GET["id"]);

            //Consultar farmacia
            $sql = "SELECT * FROM usuarios WHERE id = ?";
            if ($stmt = mysqli_prepare($adminLink, $sql)) {
                //Enlazar parámetros
                mysqli_stmt_bind_param($stmt, "i", $paramId);

                $paramId = $id;
                if (mysqli_stmt_execute($stmt)) {
                    $result = mysqli_stmt_get_result($stmt);

                    if (mysqli_num_rows($result) == 1) {
                        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                        //Recuperar datos del usuario
                        $name = $row["nombre"];
                        $username = $row["usuario"];
                        $role_id = $row["rol_id"];

                    } else {
                        header("location: error.php");
                        exit();

                    }
                } else {
                    echo "Hubo un error al obtener la información del usuario.";
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
                <h2 class="mt-5">Editar usuario</h2>
                <p>Puede editar los campos para actualizar el registro de un usuario.</p>
                <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" name="name"
                               class="form-control <?php echo (!empty($nameError)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $name; ?>">
                        <span class="invalid-feedback"><?php echo $nameError; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Usuario</label>
                        <input type="text" name="username" disabled
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
