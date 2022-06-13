<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Error</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .wrapper{
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
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php
                if (isset($_GET["unauthorized"]) == true) {
                    echo '<h2 class="mt-5 mb-3 text-center">Usuario no autorizado.</h2>';
                    echo '<div class="alert alert-danger text-center">El acceso a esta página está denegado.</div>';
                    echo '<div class="text-center"><a href="index.php" class="btn btn-primary text-center">Volver</a></div>';
                } else {
                    echo '<h2 class="mt-5 mb-3 text-center">No se pudo realizar la petición</h2>';
                    echo '<div class="alert alert-danger text-center">Se ha detectado una petición inválida.</div>';
                    echo '<div class="text-center"><a href="index.php" class="btn btn-primary text-center">Volver</a></div>';
                }
                ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>