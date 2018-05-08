<?php
require_once 'config.php';

//Define variables y las inicia vacias
$name=$address=$salary="";
$name_err=$address_err=$salary_err="";

//Procesar datos del formulario al enviar
if($_SERVER["REQUEST_METHOD"]=="POST"){
    //Validar nombre
    $input_name=trim($_POST["name"]);
    if(empty($input_name)){
        $name_err="Introduce un nombre.";
    }elseif(preg_match("/[0-9]/",$input_name)){
        $name_err="No se permiten numeros.";    
    }else{
        $name=$input_name;
    }
    //Validar direccion
    $input_address=trim($_POST["address"]);
    if(empty($input_address)){
        $address_err='Introduce una direccion valida.';
    }else{
        $address=$input_address;
    }
    //Validar salario
    $input_salary=trim($_POST["salary"]);
    if(empty($input_salary)){
        $salary_err='Introduce un valor de salario.';
    }elseif(!ctype_digit($input_salary)){
        $salary_err='Introduce un valor positivo.';
    }else{
        $salary=$input_salary;
    }
    //Comprobar errores antes de insertar
    if(empty($name_err)&&empty($address_err)&&empty($salary_err)){
        //Preparar insertar
        $sql="INSERT INTO employees (name,address,salary)VALUES(?,?,?)";
        if($stmt=mysqli_prepare($link,$sql)){
            //Unir variables a insertar
            mysqli_stmt_bind_param($stmt,"sss",$param_name,$param_address,$param_salary);
            //Parametros
            $param_name=$name;
            $param_address=$address;
            $param_salary=$salary;
            
            //Intentar ejecutar query
            if(mysqli_stmt_execute($stmt)){
                //Redirigir a index
                header("location: index.php");
                exit();
            }else{
                echo "Algo a salido mal. Prueba de nuevo";
            }
        }
        //Cerrar query
        mysqli_stmt_close($stmt);
    }
    //Cerrar conexion
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
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
                            <h2>Crear entrada</h2>
                        </div>
                        <p>Please fill this form and submit to add employee record to the database.</p>
                        <form  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="form-group <?php echo (!empty($name_err)) ? 'has-error':''; ?>">
                                <label>Nombre</label>
                                <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                                <span class="help-block" ><?php echo $name_err; ?></span>
                            </div>
                            <div class="form-group <?php echo (!empty($address_err)) ? 'has-error':''; ?>">
                                <label>Direccion</label>
                                <textarea name="address"class="form-control" value="<?php echo $address; ?>"></textarea>
                                <span class="help-block"><?php echo $address_err;?></span>
                            </div>
                            <div class="form-group <?php echo (!empty($salary_err)) ? 'has-error':''; ?>">
                                <label>Salario</label>
                                <input type="text" name="salary" class="form-control" value="<?php echo $salary; ?>">
                                <span class="help-block"><?php echo $salary_err; ?></span>
                            </div>
                            <input type="submit" class="btn btn-primary" value="Submit">
                            <a href="index.php" class="btn btn-default">Cancel</a>
                        </form>
                    </div>
                </div>        
            </div>
        </div>
    </body>
</html>