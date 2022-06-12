<?php
session_start();
require_once "config.php";

//Check if there are a current session
if (session_status() == PHP_SESSION_NONE) {
    //Redirect to index
    header("location: ../php/farmacias/index.php");
}

//Variables de login
$username = "";
$password = "";
$loginError = "";
$passwordError = "";
$usernameError = "";

//Petición de login
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //Validación del nombre de usuario
    $tmpUsername = trim($_POST["username"]);
    if (empty($tmpUsername)) {
        $usernameError = "Ingrese su nombre de usuario";
    } else {
        $username = $tmpUsername;
    }

    //Validación de contraseña
    $tmpPassword = trim($_POST["password"]);
    if (empty($tmpPassword)) {
        $passwordError = "La contraseña no puede quedar en blanco";
    } else {
        $password = $tmpPassword;
    }


    //Validar credenciales
    if (empty($usernameError) && empty($passwordError)) {

        //Preparar consulta para verificar usuario
        $sql = "SELECT * FROM usuarios WHERE usuario = ?";

        if ($stmt = mysqli_prepare($adminLink, $sql)) {
            //Enlazar parámetros
            mysqli_stmt_bind_param($stmt, "s", $paramUsername);
            //Asignar parámetro
            $paramUsername = $username;

            //Ejecutar consulta
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    //Obtener fila del registro encontrado
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    //Comparar contraseñas
                    if ($row["clave"] === $password) {
                        //Login exitoso, guardar datos en sesión
                        session_start();
                        $_SESSION["user_id"] = $row["id"];
                        $_SESSION["name"] = $row["nombre"];
                        $_SESSION["username"] = $row["usuario"];
                        $_SESSION["role_id"] = $row["rol_id"];

                        //Redirigir a index
                        header("location: ../php/farmacias/index.php");

                    } else {
                        $loginError = "Usuario o contraseña inválido.";
                    }

                } else {
                    $loginError = "No se encontró el usuario.";

                }

            } else {
                $loginError = "Hubo un problema al ingresar.";

            }

        }

    }

}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body style="background-image: url('../res/background.jpg')" id="full">

<div class="container">

    <div class="row align-items-center vh-100">
        <div class="col-6 mx-auto">
            <div class="card shadow border">
                <div class="card-body d-flex flex-column align-items-center">
                    <h2 class="text-center">CLÍNICA MEDICA</h2>
                    <br>

                    <?php
                    if (!empty($loginError)) {
                        echo sprintf("<div class='alert alert-danger w-50'>%s</div>", $loginError);
                    }
                    ?>

                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                        <div class="form-group w-100 text-center">
                            <label>Nombre de usuario</label>
                            <input type="text" name="username"
                                   class="form-control <?php echo (!empty($usernameError)) ? 'is-invalid' : ''; ?>"
                                   value="<?php echo $username; ?>">
                            <span class="invalid-feedback"><?php echo $usernameError; ?></span>
                        </div>

                        <div class="form-group w-100 text-center">
                            <label>Contraseña</label>
                            <input type="password" name="password"
                                   class="form-control <?php echo (!empty($passwordError)) ? 'is-invalid' : ''; ?>"
                                   value="<?php echo $password; ?>">
                            <span class="invalid-feedback"><?php echo $passwordError; ?></span>
                        </div>

                        <div class="form-group text-center">
                            <input type="submit" class="btn btn-primary" value="Iniciar sesión">
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

</body>
</html>
