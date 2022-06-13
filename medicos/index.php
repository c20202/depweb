<?php
session_start();
require_once "../config.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Médicos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        table tr td:last-child {
            width: 120px;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" style="color:white"><i class="fa fa-hospital-o"></i> Clínica médica</a>
        <div class="d-flex">
            <a href="../logout.php?location=medicos" class="btn btn-outline-light pull-right"><i class="fa fa-power-off"></i> Cerrar sesión</a>
        </div>
    </div>
</nav>


<div class="container">

    <div class="row">

        <div class="col-2 mt-5 mb-3 clearfix">
            <ul class="nav flex-column nav-pills justify-content-center">
                <li class="nav-item">
                    <a class="nav-link" href="../farmacias/index.php">Farmacias</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../medicinas/index.php">Medicinas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="index.php">Médicos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../pacientes/index.php">Pacientes</a>
                </li>
                <?php
                if ($_SESSION["role_id"] === ROLE_ADMIN) {
                    echo '<li class="nav-item">';
                    echo '<a class="nav-link" href="../usuarios/index.php">Usuarios</a>';
                    echo '</li>';
                }
                ?>
            </ul>
        </div>

        <div class="col-10">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Médicos</h2>
                        <?php
                        //Add button just for admin
                        if ($_SESSION["role_id"] == ROLE_ADMIN) {
                            echo '<a href="create.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Nuevo médico</a>';
                        }
                        ?>
                    </div>
                    <?php
                    //Query para obtener todos los medicos
                    $sql = "SELECT * FROM medicos";

                    if ($result = mysqli_query($link, $sql)) {
                        if (mysqli_num_rows($result) > 0) {
                            echo '<table class="table table-bordered table-striped">';
                            echo "<thead>";
                            echo "<tr>";
                            echo "<th>#</th>";
                            echo "<th>Médico</th>";
                            echo "<th>Especialidad</th>";
                            echo "<th>Teléfono</th>";
                            echo "<th>DUI</th>";
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            while ($row = mysqli_fetch_array($result)) {
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>" . $row['nombre'] . " " .  $row['apellido'] . "</td>";
                                echo "<td>" . $row['especialidad'] . "</td>";
                                echo "<td>" . $row['telefono'] . "</td>";
                                echo "<td>" . $row['dui'] . "</td>";
                                echo "<td>";
                                echo '<a href="read.php?id=' . $row['id'] . '" class="mr-3" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                //Update and delete just for admin
                                if ($_SESSION["role_id"] === ROLE_ADMIN) {
                                    echo '<a href="update.php?id=' . $row['id'] . '" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                    echo '<a href="delete.php?id=' . $row['id'] . '" class="mr-3" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                                }
                                echo "</td>";
                                echo "</tr>";
                            }
                            echo "</tbody>";
                            echo "</table>";
                            mysqli_free_result($result);
                        } else {
                            echo '<div class="alert alert-danger"><em>No se encontraron resultados.</em></div>';
                        }
                    } else {
                        echo "Hubo un error al obtener los médicos.";
                    }
                    mysqli_close($link);
                    ?>
                </div>
            </div>
        </div>

    </div>

</div>
</body>
</html>

