<?php
// Procesar operacion de borrado despues de comprobar
if(isset($_POST["id"]) && !empty($_POST["id"])){
    
    require_once 'config.php';
    
    // Preparar sentencia de borrado
    $sql = "DELETE FROM employees WHERE id = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        //Unir variable a sentencia
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
       //Unir parametro a variable
        $param_id = trim($_POST["id"]);
        
        //Ejecutar sentencia
        if(mysqli_stmt_execute($stmt)){
            //Borrado correcto
            header("location: index.php");
            exit();
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
     
 
    mysqli_stmt_close($stmt);
    
    
    mysqli_close($link);
} else{
    //Comprobar existencia de parametro id
    if(empty(trim($_GET["id"]))){
       
        header("location: error.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h1>Borrar Entrada</h1>
                    </div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger fade in">
                            <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>"/>
                            <p>¿Seguro que quieres borrarlo?</p><br>
                            <p>
                                <input type="submit" value="Yes" class="btn btn-danger">
                                <a href="index.php" class="btn btn-default">No</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>