<?php
require_once 'config.php';

//Definir variables vacias
$name=$address=$salary="";
$name_err=$address_err=$salary_err="";

//Procesar datos de formulario cuando se envia

if(isset($_POST["id"]) && !empty($_POST["id"])){
    //Obtener valores ocultos de input
    $id=$_POST["id"];

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
    //Comprobar errores de input
    if(empty($name_err)&&empty($address_err)&&empty($salary_err)){
        //Preparar query
        $sql="UPDATE employees SET name=?,address=?,salary=? WHERE id=?";

        if($stmt=mysqli_prepare($link,$sql)){
            //Unir variables a la sentencia preparada
            mysqli_stmt_bind_param($stmt,"ssii",$param_name,$param_address,$param_salary,$param_id);
            $param_name = $name;
            $param_address = $address;
            $param_salary = $salary;
            $param_id = $id;
        
            if(mysqli_stmt_execute($stmt)){
                header("location: index.php");
                exit();
            }else{
                echo "Algo a salido mal";
            }
        }
        //Cerrar sentencia 
        mysli_stmt_close($stmt);
    }
    //Cerrar conexion
    mysqli_close($link);
}else{
    if(isset($_GET["id"])&&!empty(trim($_GET["id"]))){
        //Obtener parametro URL
        $id=trim($_GET["id"]);

        //Preparar sentencia preparada
        $sql="SELECT * FROM  employees WHERE id=?";
        if($stmt=mysqli_prepare($link,$sql)){
            //Unir variables a los parametros de la sentencia
            mysqli_stmt_bind_param($stmt,"i",$param_id);
            
            //Ajustar parametros
            $param_id=$id;

            //Intento de ejecutar la sentencia
            if(mysqli_stmt_execute($stmt)){
                $result=mysqli_stmt_get_result($stmt);

                if(mysqli_num_rows($result)==1){
                    $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
                    $name = $row["name"];
                    $address = $row["address"];
                    $salary = $row["salary"];
                }else{
                    //URL no contiene id valida
                    header("location: error.php");
                    exit();
                }
            }else{
                echo "Algo a salido mal";
            }

        }
        mysqli_stmt_close($stmt);
        mysqli_close($link);
    }else{
        header("location: error.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
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
                        <h2>Update Record</h2>
                    </div>
                    <p>Please edit the input values and submit to update the record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                            <span class="help-block"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($address_err)) ? 'has-error' : ''; ?>">
                            <label>Address</label>
                            <textarea name="address" class="form-control"><?php echo $address; ?></textarea>
                            <span class="help-block"><?php echo $address_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($salary_err)) ? 'has-error' : ''; ?>">
                            <label>Salary</label>
                            <input type="text" name="salary" class="form-control" value="<?php echo $salary; ?>">
                            <span class="help-block"><?php echo $salary_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>